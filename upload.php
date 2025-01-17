<?php
// upload.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Очистка имени проекта: Разрешены буквы, цифры, пробелы, дефисы и подчёркивания
    $project_name = trim($_POST['project_name']);
    $project_name = preg_replace('/[^A-Za-z0-9_\-\s]/', '', $project_name);
    $project_name = preg_replace('/\s+/', '_', $project_name); // Замена пробелов на "_"

    if (empty($project_name)) {
        header("Location: /index?error=" . urlencode("Название проекта не может быть пустым или некорректным."));
        exit;
    }

    $description = trim($_POST['description']);
    $files_dir = __DIR__ . '/FILES/' . $project_name;

    // Проверка наличия описания
    if (empty($description)) {
        header("Location: /index?error=" . urlencode("Описание проекта не может быть пустым."));
        exit;
    }

    // Проверка существования проекта
    if (file_exists($files_dir)) {
        header("Location: /index?error=" . urlencode("Проект с таким названием уже существует."));
        exit;
    }

    // Создание директории проекта
    if (!mkdir($files_dir, 0755, true)) {
        header("Location: /index?error=" . urlencode("Не удалось создать директорию для проекта."));
        exit;
    }

    // Создание файла .htaccess для защиты директории
    $htaccess_content = <<<HTACCESS
<FilesMatch "^\.">
    Require all denied
</FilesMatch>
HTACCESS;

    if (file_put_contents("$files_dir/.htaccess", $htaccess_content) === false) {
        header("Location: /index?error=" . urlencode("Не удалось создать .htaccess файл для проекта."));
        exit;
    }

    // Обработка загрузки файлов
    foreach ($_FILES['files']['name'] as $key => $name) {
        if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
            // Проверка размера файла (до 300 МБ)
            if ($_FILES['files']['size'][$key] > 300 * 1024 * 1024) {
                header("Location: /index?error=" . urlencode("Файл {$name} превышает максимальный размер 300 МБ."));
                exit;
            }

            // Оригинальное имя файла с фильтрацией запрещённых символов
            $clean_name = trim($name);
            $clean_name = preg_replace('/[^A-Za-z0-9_\-\.\s]/', '', $clean_name);
            $clean_name = preg_replace('/\s+/', '_', $clean_name); // Замена пробелов на "_"

            if (empty($clean_name)) {
                header("Location: /index?error=" . urlencode("Файл с некорректным именем не может быть загружен."));
                exit;
            }

            $tmp_name = $_FILES['files']['tmp_name'][$key];
            $destination = "$files_dir/" . $clean_name;

            // Перемещение загруженного файла в директорию проекта
            if (!move_uploaded_file($tmp_name, $destination)) {
                header("Location: /index?error=" . urlencode("Не удалось загрузить файл: {$name}"));
                exit;
            }
        } else {
            // Обработка ошибок загрузки
            header("Location: /index?error=" . urlencode("Ошибка при загрузке файла: {$name}"));
            exit;
        }
    }

    // Сохранение описания проекта
    if (file_put_contents("$files_dir/description.txt", $description) === false) {
        header("Location: /index?error=" . urlencode("Не удалось сохранить описание проекта."));
        exit;
    }

    // Перенаправление с сообщением об успехе
    header("Location: /index?success=" . urlencode("Проект успешно загружен."));
    exit;
}
?>
