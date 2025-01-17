<?php
// delete.php

session_start();

// Проверка, авторизован ли администратор
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php?error=" . urlencode("Доступ запрещен. Пожалуйста, войдите в админ-панель."));
    exit;
}

// Проверка, передано ли имя проекта
if (!isset($_GET['name']) || empty($_GET['name'])) {
    header("Location: admin.php?error=" . urlencode("Проект не указан."));
    exit;
}

// Очистка имени проекта
$project_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $_GET['name']);
$project_dir = __DIR__ . '/FILES/' . $project_name;

// Проверка существования директории проекта
if (!is_dir($project_dir)) {
    header("Location: admin.php?error=" . urlencode("Проект не найден."));
    exit;
}

// Функция для рекурсивного удаления директории
function rrmdir($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            rrmdir($path); // Рекурсивное удаление поддиректорий
        } else {
            if (!unlink($path)) {
                return false; // Не удалось удалить файл
            }
        }
    }

    return rmdir($dir); // Удаление самой директории
}

// Попытка удалить проект
if (rrmdir($project_dir)) {
    header("Location: admin.php?success=" . urlencode("Проект успешно удален."));
    exit;
} else {
    header("Location: admin.php?error=" . urlencode("Не удалось удалить проект. Проверьте права доступа."));
    exit;
}
?>
