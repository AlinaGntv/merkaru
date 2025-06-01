@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-image">
                    <img src="{{ asset('assets/images/model.png') }}" alt="Модель">
                </div>
                <div class="hero-right">
                    <div class="hero-text">
                        <h2>Идеальная одежда по вашим меркам</h2>
                        <p>Ваш идеальный выбор для одежды на заказ</p>
                    </div>
                    <div class="hero-form">
                        <h3>Зарегистрируйтесь прямо сейчас!</h3>
                        <form action="#">
                            <input type="email" placeholder="example@yandex.ru" required>
                            <button type="submit">Продолжить регистрацию</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.sections.steps')
    @include('partials.sections.features')
    @include('partials.sections.cta')
    @include('partials.sections.benefits')

    <section class="feedback-section">
        <div class="container">
            <h2>Обратная связь</h2>
            <form action="{{ route('feedback.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" placeholder="Ваше имя" required 
                        value="{{ auth()->user() ? auth()->user()->name : '' }}">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Ваш email" required
                        value="{{ auth()->user() ? auth()->user()->email : '' }}">
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Ваше сообщение" required></textarea>
                </div>
                <button type="submit">Отправить</button>
            </form>
        </div>
    </section>
    
@endsection