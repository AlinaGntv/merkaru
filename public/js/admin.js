document.addEventListener('DOMContentLoaded', function() {
    // Обработчик кнопки выхода
    document.getElementById('logoutButton')?.addEventListener('click', function() {
        document.getElementById('logout-form').submit();
    });
    
    // Можно добавить AJAX-загрузку данных
});