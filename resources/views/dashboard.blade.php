@extends('layouts.app')

@section('content')
<section class="dashboard">
    <div class="container">
        <h2>Личный кабинет</h2>
        <p>Добро пожаловать, <span id="user-name">{{ Auth::user()->name ?? 'Гость' }}</span>!</p>

        @if(session('success') || $showSuccess ?? false)
            <div class="alert alert-success">
                Заказ успешно создан!
            </div>
        @endif

        {{-- Секция "Мои заказы" --}}
        <section class="my-orders mb-5">
            <h3>Мои заказы</h3>
            
            @if($orders->count() > 0)
                <div class="orders-list">
                    @foreach($orders as $order)
                    <div class="order-card mb-3 p-3 border rounded" data-id="{{ $order->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Заказ #{{ $order->id }}</h5>
                            <span class="badge 
                                @if($order->status == 'new') bg-primary
                                @elseif($order->status == 'processing') bg-warning
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @endif">
                                {{ $order->status_text }}
                            </span>
                        </div>
                        <p class="mb-1"><strong>Модель:</strong> {{ $order->model_type_text }}</p>
                        <p class="mb-1"><strong>Сумма:</strong> {{ $order->total_amount }} ₽</p>
                        <p class="mb-1"><strong>Дата:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        
                        <div class="collapse mt-2" id="orderDetails{{ $order->id }}">
                            <div class="card card-body">
                                <p><strong>Цвет:</strong> 
                                    <span class="color-badge" style="background-color: {{ $order->color }}"></span>
                                </p>
                                <p><strong>Материал:</strong> {{ $order->material_text }}</p>
                                <p><strong>Адрес:</strong> {{ $order->address_data['address'] ?? 'Не указан' }}</p>
                                
                                <p><strong>Размеры:</strong> 
                                
                                @php
                                    $sizeLabels = [
                                        'hips' => 'Бедра',
                                        'chest' => 'Грудь',
                                        'waist' => 'Талия',
                                        'shoulder_width' => 'Ширина плеч',
                                        'sleeve_length' => 'Длина рукава',
                                        'neck_circumference' => 'Обхват шеи',
                                        'wrist_circumference' => 'Обхват запястья',
                                        'thigh_circumference' => 'Обхват бедра',
                                        'knee_circumference' => 'Обхват колена',
                                        'inseam_length' => 'Длина штанины',
                                        'height' => 'Рост',
                                    ];
                                @endphp

                                <ul>
                                    @foreach($order->size_data as $key => $value)
                                        @if(is_numeric($value))
                                            <li>{{ $sizeLabels[$key] ?? $key }}: {{ $value }} см</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @if($order->comment)
                                <div class="card shadow-sm animate-fade mt-3">
                                    <div class="card-header bg-white border-bottom">
                                        <h5 class="mb-0">Ваш комментарий</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $order->comment }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    У вас пока нет заказов
                </div>
            @endif
        </section>

        {{-- Шаг 1: Модель --}}
        <section class="step" id="step-model">
            <h3>Выберите модель одежды</h3>
            <div class="model-selector-grid">
                @foreach (['tshirt' => 'Футболка', 'hoodie' => 'Толстовка', 'dress' => 'Платье', 'suit' => 'Костюм'] as $key => $label)
                <div class="model-option" data-model="{{ $key }}">
                    <img src="{{ asset("assets/images/products/product" . ($loop->index + 1) . ".png") }}" alt="{{ $label }}">
                    <span>{{ $label }}</span>
                    <input type="radio" name="model" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn next-step" data-next="#step-settings">Далее</button>
        </section>

        {{-- Шаг 2: Настройки --}}
        <section class="step d-none" id="step-settings">
            <h3>Настройка модели</h3>
            <form id="model-settings">
                @csrf
                <input type="hidden" name="model" id="selected-model">

                <label>Цвет:</label>
                <div class="color-palette">
                    @php
                        $colors = [
                            '#ffffff' => 'Белый',
                            '#000000' => 'Чёрный',
                            '#ff0000' => 'Красный',
                            '#00ff00' => 'Зелёный',
                            '#0000ff' => 'Синий',
                            '#ffff00' => 'Жёлтый',
                            '#ffa500' => 'Оранжевый',
                            '#800080' => 'Фиолетовый',
                        ];
                    @endphp
                    @foreach ($colors as $hex => $name)
                    <div class="color-circle" tabindex="0" role="radio" aria-checked="false" data-color="{{ $hex }}" title="{{ $name }}" style="background-color: {{ $hex }};"></div>
                    @endforeach
                </div>
                <input type="hidden" name="color" id="color" value="#ffffff">

                <label for="material">Материал:</label>
                <div class="custom-select-wrapper">
                    <select id="material" name="material" class="custom-select">
                        <option value="cotton" data-icon="🌿">Хлопок</option>
                        <option value="polyester" data-icon="⚡">Полиэстер</option>
                        <option value="wool" data-icon="🐑">Шерсть</option>
                    </select>
                </div>

                <button type="button" class="btn next-step" data-next="#step-size">Далее</button>
            </form>
        </section>


        {{-- Шаг 3: Размеры (будут зависеть от модели) --}}
        <section class="step d-none" id="step-size">
            <h3>Ваши размеры</h3>
            <form id="size-form" method="POST" action="{{ route('sizes.store') }}">
                @csrf

                <label for="height">Рост (см):</label>
                <input type="number" id="height" name="height" required min="50" max="250" step="0.1">

                <label for="chest">Обхват груди (см):</label>
                <input type="number" id="chest" name="chest" required min="40" max="200" step="0.1">

                <label for="waist">Обхват талии (см):</label>
                <input type="number" id="waist" name="waist" min="40" max="200" step="0.1">

                <label for="hips">Обхват бёдер (см):</label>
                <input type="number" id="hips" name="hips" min="40" max="200" step="0.1">

                <label for="shoulder_width">Ширина плеч (см):</label>
                <input type="number" id="shoulder_width" name="shoulder_width" min="20" max="80" step="0.1">

                <label for="sleeve_length">Длина рукава (см):</label>
                <input type="number" id="sleeve_length" name="sleeve_length" min="20" max="80" step="0.1">

                <label for="neck_circumference">Обхват шеи (см):</label>
                <input type="number" id="neck_circumference" name="neck_circumference" min="20" max="60" step="0.1">

                <label for="wrist_circumference">Обхват запястья (см):</label>
                <input type="number" id="wrist_circumference" name="wrist_circumference" min="10" max="40" step="0.1">

                <label for="thigh_circumference">Обхват бедра (см):</label>
                <input type="number" id="thigh_circumference" name="thigh_circumference" min="30" max="100" step="0.1">

                <label for="knee_circumference">Обхват колена (см):</label>
                <input type="number" id="knee_circumference" name="knee_circumference" min="20" max="70" step="0.1">

                <label for="inseam_length">Длина штанины (см):</label>
                <input type="number" id="inseam_length" name="inseam_length" min="40" max="120" step="0.1">

                <button type="button" class="btn next-step" data-next="#step-address">Далее</button>
            </form>
        </section>


        {{-- Шаг 4: Адрес --}}
        <section class="step d-none" id="step-address">
            <h3>Адрес доставки</h3>
            <form id="address-form" method="POST" action="{{ route('address.store') }}">
                @csrf
                <label for="address">Адрес:</label>
                <input type="text" id="address" name="address" required>
                <button type="button" class="btn next-step" data-next="#step-submit">Далее</button>
            </form>
        </section>

        {{-- Шаг 5: Подтверждение и оплата --}}
        <section class="step d-none" id="step-submit">
            <h3>Подтверждение и оплата</h3>
            
            <div class="order-summary mb-4">
                <h4>Детали заказа:</h4>
                <div id="order-details">
                </div>
                <p class="mt-3">Итоговая сумма: <strong id="order-total">—</strong> ₽</p>
            </div>

            <div class="mb-3">
                <label for="order-comment" class="form-label">Комментарий к заказу (необязательно):</label>
                <textarea class="form-control" id="order-comment" name="comment" rows="3" placeholder="Ваши пожелания или особые указания"></textarea>
            </div>

            <form id="order-form" method="POST" action="{{ route('orders.create') }}">
                @csrf
                <input type="hidden" name="model_type" id="order-model-type">
                <input type="hidden" name="color" id="order-color">
                <input type="hidden" name="material" id="order-material">
                
                <input type="hidden" name="payment_method" id="final-payment-method" value="sbp">
                <button type="submit" class="btn btn-primary">Подтвердить и оплатить</button>
            </form>
        </section>

    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush