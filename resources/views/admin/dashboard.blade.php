@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="container">
        <h1>Административная панель</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn">Выйти</button>
        </form>
    </div>
</div>

<main class="admin-content">
    <!-- Заказы -->
    <section class="admin-section">
        <h2>Последние заказы</h2>
        <div class="data-container">
            @forelse($orders as $order)
                <div class="order-item">
                    <p>Заказ #{{ $order->id }} - {{ $order->created_at->format('d.m.Y H:i') }}</p>
                </div>
            @empty
                <p>Заказы отсутствуют.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.orders') }}" class="btn">Все заказы</a>
    </section>

    </section>

    <!-- Сообщения -->
    <section class="admin-section">
        <h2>Обратная связь</h2>
        <div class="data-container">
            @forelse($feedbackMessages as $message)
                <div class="feedback-item">
                    <div class="feedback-header">
                        <strong>{{ $message->name }}</strong>
                        <span>{{ $message->email }}</span>
                        <small>{{ $message->created_at->format('d.m.Y H:i') }}</small>
                    </div>
                    <div class="feedback-body">
                        <p>{{ $message->message }}</p>
                    </div>
                    @if($message->user)
                        <div class="feedback-user">
                            Пользователь: {{ $message->user->name }} (ID: {{ $message->user->id }})
                        </div>
                    @endif
                </div>
            @empty
                <p>Сообщения отсутствуют.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.feedback') }}" class="btn btn-primary">
            <i class="fas fa-envelope"></i> Все сообщения
        </a>
    </section>
</main>
@endsection