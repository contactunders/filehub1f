<?php
// project.php

// Убедитесь, что нет пробелов или пустых строк перед этим тегом

if (!isset($_GET['name'])) {
    echo "Проект не указан.";
    exit;
}

$project_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $_GET['name']);
$project_dir = __DIR__ . '/FILES/' . $project_name;

// Проверка существования проекта
if (!is_dir($project_dir)) {
    echo "Проект не найден.";
    exit;
}

// Загрузка информации о проекте
$description_file = "$project_dir/description.txt";
$description = '';
if (file_exists($description_file)) {
    $description = file_get_contents($description_file);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($project_name); ?></title>
    <link rel="stylesheet" href="/styles.css">
    <!-- Подключение Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($project_name); ?></h1>
        <br><br>
        <a href="/index" class="back-button"><i class="fas fa-arrow-left"></i> Назад</a>
    </header>
    <main>
        <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
        <ul>
            <?php
            // Получаем список файлов, исключая .htaccess, project.php и description.txt
            $files = array_diff(scandir($project_dir), array('.', '..', 'project.php', 'description.txt', '.htaccess'));
            foreach ($files as $file) {
                // Пропуск скрытых файлов (начинающихся с точки)
                if (substr($file, 0, 1) === '.') {
                    continue;
                }

                // Генерация URL для файла
                $file_url = '/FILES/' . urlencode($project_name) . '/' . urlencode($file);
                echo "<li><a href=\"{$file_url}\" download>" . htmlspecialchars($file) . "</a></li>";
            }
            ?>
        </ul>
        <a href="/download_zip.php?name=<?php echo urlencode($project_name); ?>" class="button">Скачать все файлы как ZIP</a>
    </main>
    <footer>
        <p>Мы не несем ответственности за размещенный контент и возможное заражение вирусами. Все права защищены. Если вы обнаружили контент с GitHub или подозреваете вирусную программу, вы можете подать жалобу, и она будет удалена. Свяжитесь с нами: <a href="mailto:filehub@mail.1fproject.ru">filehub@mail.1fproject.ru</a></p>
    </footer>
    <!-- Подключение Font Awesome для иконок -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-pQg5o0Nw3QZ8ZtU1KY/X0nPvE7o4Qkk+YZqVQq+Q8lE+n05N1j0TgwjSxa+k3Qd5EmYqqRQfwR4HI8RWouDjCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
