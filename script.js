// script.js

document.addEventListener('DOMContentLoaded', function() {
    // Поиск элемента кнопки сворачивания
    const toggleButton = document.getElementById('toggle-upload');
    const uploadSection = document.getElementById('upload-section');

    if (toggleButton && uploadSection) {
        toggleButton.addEventListener('click', function() {
            uploadSection.classList.toggle('collapsed');

            // Изменение иконки и текста кнопки в зависимости от состояния
            if (uploadSection.classList.contains('collapsed')) {
                toggleButton.innerHTML = '<i class="fas fa-folder-plus"></i> Загрузить проект';
            } else {
                toggleButton.innerHTML = '<i class="fas fa-folder-minus"></i> Скрыть форму';
            }
        });
    }

    // Поиск элемента поиска
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let projects = document.querySelectorAll('.project-item');
            projects.forEach(function(project) {
                let text = project.textContent.toLowerCase();
                if (text.includes(filter)) {
                    project.style.display = 'flex';
                } else {
                    project.style.display = 'none';
                }
            });
        });
    }
});
