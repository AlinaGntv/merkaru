@extends('layouts.app')

@section('content')
    <section class="catalog">
        <div class="container">
            <h2>Наша продукция</h2>
            <p>Мы предлагаем широкий ассортимент одежды, выполненной по индивидуальным меркам. Выберите то, что подходит именно вам!</p>

            <div class="product-list">
                @foreach($products as $product)
                    <div class="product-item">
                        <img src="{{ asset('assets/images/products/' . $product['image']) }}" alt="{{ $product['name'] }}">
                        <h3>{{ $product['name'] }}</h3>
                        <p>{{ $product['description'] }}</p>

                        @auth
                            <a href="{{ route('dashboard') }}" class="btn order-btn">Заказать</a>
                        @endauth

                        @guest
                            <button class="btn order-btn" data-auth="guest" data-product-id="{{ $product['id'] }}">Заказать</button>
                        @endguest

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Модальное окно авторизации -->
    <div id="loginModal" class="merka-modal">
        <div class="merka-modal-content">
            <span class="merka-close">&times;</span>
            <h2>Вход в систему</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="product_id" id="product_id">
                <input type="email" name="email" placeholder="Ваш email" required>
                <input type="password" name="password" placeholder="Ваш пароль" required>
                <button type="submit">Войти и оформить заказ</button>
            </form>
            <p class="text-center">Нет аккаунта? <a href="#" class="show-register">Зарегистрироваться</a></p>
        </div>
    </div>
@endsection