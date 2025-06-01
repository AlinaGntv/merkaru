<!-- Модальное окно входа -->
<div id="loginModal" class="merka-modal">
    <div class="merka-modal-content">
        <span class="merka-close">&times;</span>
        <h2>Вход в систему</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="product_id" id="product_id">
            <input type="email" name="email" placeholder="Ваш email" required>
            <input type="password" name="password" placeholder="Ваш пароль" required>
            <button type="submit">Войти</button>
        </form>
        <p class="text-center">Нет аккаунта? <a href="#" class="show-register">Зарегистрироваться</a></p>
    </div>
</div>

<!-- Модальное окно регистрации -->
<div id="registerModal" class="merka-modal">
    <div class="merka-modal-content">
        <span class="merka-close">&times;</span>
        <h2>Регистрация</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="email" name="email" placeholder="Ваш email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="password" name="password_confirmation" placeholder="Повторите пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>