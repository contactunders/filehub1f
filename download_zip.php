<?php
// download_zip.php

ob_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

function send_error($message) {
    // Очищаем буфер вывода
    ob_end_clean();
    header('Content-Type: text/plain; charset=UTF-8');
    echo $message;
    exit;
}

if (!isset($_GET['name'])) {
    send_error("Проект не указан.");
}

$project_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $_GET['name']);
$project_dir = __DIR__ . '/FILES/' . $project_name;

// Проверяем, существует ли проект
if (!is_dir($project_dir)) {
    send_error("Проект не найден.");
}

// Проверка наличия расширения ZipArchive
if (!class_exists('ZipArchive')) {
    send_error("Расширение ZipArchive не установлено.");
}

$zip = new ZipArchive();
$zip_filename = tempnam(sys_get_temp_dir(), "zip");

if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
    send_error("Не удалось создать ZIP-архив.");
}

// Добавляем файлы в ZIP
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($project_dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if (!$file->isFile()) continue;

    $basename = $file->getBasename();

    // Исключаем из архива .htaccess, description.txt и любые скрытые файлы
    if ($basename === '.htaccess' || $basename === 'description.txt' || $basename === 'project.php' || substr($basename, 0, 1) === '.') {
        continue;
    }

    $file_path = $file->getRealPath();
    $relative_path = substr($file_path, strlen($project_dir) + 1);
    $zip->addFile($file_path, $relative_path);
}

$zip->close();

if (!file_exists($zip_filename)) {
    send_error("ZIP-архив не был создан.");
}

// Отправляем ZIP файл пользователю
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($project_name) . '.zip"');
header('Content-Length: ' . filesize($zip_filename));
readfile($zip_filename);

// Удаляем временный ZIP файл
unlink($zip_filename);
exit;
?>
