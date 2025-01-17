<?php
// admin.php

session_start();

$admin_password = 'admin123'; // Измените на свой пароль

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin'] = true;
    } else {
        // Перенаправление с сообщением об ошибке
        header("Location: admin.php?error=" . urlencode("Неверный пароль."));
        exit;
    }
}

if (!isset($_SESSION['admin'])) {
    // Получение сообщения об ошибке, если есть
    $error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Админ-панель</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Вход в Админ-панель</h1>
        </header>
        <main>
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit">Войти</button>
            </form>
        </main>
        <footer>
            <p>Мы не несем ответственности за размещенный контент и возможное заражение вирусами. Все права защищены. Если вы обнаружили контент с GitHub или подозреваете вирусную программу, вы можете подать жалобу, и она будет удалена. Свяжитесь с нами: <a href="mailto:filehub@mail.1fproject.ru">filehub@mail.1fproject.ru</a></p>
        </footer>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Админ-панель</h1>
        <a href="index.php">Главная</a>
        <a href="logout.php">Выйти</a>
    </header>
    <main>
        <?php
        // Получение сообщений об успехе или ошибках
        if (isset($_GET['success'])) {
            echo "<div class='message success'>" . htmlspecialchars($_GET['success']) . "</div>";
        }
        if (isset($_GET['error'])) {
            echo "<div class='message error'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>
        <h2>Управление проектами</h2>
        <ul>
            <?php
            $files_dir = 'FILES';
            if (!is_dir($files_dir)) {
                mkdir($files_dir, 0755, true);
            }
            $projects = array_filter(glob($files_dir . '/*'), 'is_dir');
            foreach ($projects as $project) {
                $project_name = basename($project);
                echo "<li>" . htmlspecialchars($project_name) . " <a href='delete.php?name=" . urlencode($project_name) . "' onclick=\"return confirm('Удалить проект?');\">Удалить</a></li>";
            }
            ?>
        </ul>
    </main>
    <footer>
        <p>Мы не несем ответственности за размещенный контент и возможное заражение вирусами. Все права защищены. Если вы обнаружили ваш контент или подозреваете вирусную программу, вы можете подать жалобу, и она будет удалена. Свяжитесь с нами: <a href="mailto:filehub@mail.1fproject.ru">filehub@mail.1fproject.ru</a></p>
    </footer>
</body>
</html>
