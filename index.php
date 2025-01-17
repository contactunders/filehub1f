<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FileHub</title>
    <link rel="stylesheet" href="/styles.css">
    <!-- Подключение Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>FileHub</h1>
        <input type="text" id="search" placeholder="Поиск проектов...">
    </header>
    <main>
        <!-- Отображение сообщений об ошибках и успехе -->
        <?php
        if (isset($_GET['error'])) {
            echo "<div class='message error'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        if (isset($_GET['success'])) {
            echo "<div class='message success'>" . htmlspecialchars($_GET['success']) . "</div>";
        }
        ?>
        <!-- Кнопка для сворачивания/разворачивания формы загрузки -->
        <button id="toggle-upload" class="button toggle-button"><i class="fas fa-folder-plus"></i> Загрузить проект</button>
        
        <!-- Свертываемый раздел загрузки проекта -->
        <section class="upload-section collapsed" id="upload-section">
            <h2>Загрузить проект</h2>
            <form action="/upload.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="project_name" placeholder="Название проекта" required>
                <textarea name="description" placeholder="Описание проекта" required></textarea>
                <input type="file" name="files[]" multiple required>
                <button type="submit">Загрузить</button>
            </form>
        </section>
        
        <section class="projects-section">
            <h2>Проекты</h2>
            <div id="projects-list">
                <?php
                $files_dir = 'FILES';
                if (!is_dir($files_dir)) {
                    mkdir($files_dir, 0755, true);
                }
                $projects = array_filter(glob($files_dir . '/*'), 'is_dir');
                foreach ($projects as $project) {
                    $project_name = basename($project);
                    echo "<div class='project-item'><a href='/project.php?name=" . urlencode($project_name) . "'>" . htmlspecialchars($project_name) . "</a></div>";
                }
                ?>
            </div>
        </section>
    </main>
    <footer>
        <p>Мы не несем ответственности за размещенный контент и возможное заражение вирусами. Все права защищены. Если вы обнаружили контент с GitHub или подозреваете вирусную программу, вы можете подать жалобу, и она будет удалена. Свяжитесь с нами: <a href="mailto:filehub@mail.1fproject.ru">filehub@mail.1fproject.ru</a></p>
    </footer>
    <!-- Подключение Font Awesome для иконок -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-pQg5o0Nw3QZ8ZtU1KY/X0nPvE7o4Qkk+YZqVQq+Q8lE+n05N1j0TgwjSxa+k3Qd5EmYqqRQfwR4HI8RWouDjCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/script.js"></script>
</body>
</html>
