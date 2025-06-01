<header>
    <div class="container">
        <a href="{{ route('home') }}" class="logo-link">
            <div class="logo">
                <h1>МЕРКА.РУ</h1>
            </div>
        </a>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Главная</a></li>
                <li><a href="{{ route('catalog') }}">Продукция</a></li>
                <li><a href="{{ route('contacts') }}">Контакты</a></li>
                
                @auth
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('admin.dashboard') }}">Админпанель</a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}">Личный кабинет</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-btn">Выйти</button>
                        </form>
                    </li>
                @else
                    <li><a href="#" class="nav-btn login-btn">Войти</a></li>
                    <li><a href="#" class="nav-btn register-btn">Регистрация</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>