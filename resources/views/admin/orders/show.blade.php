@extends('layouts.app')

@section('title', 'Детали заказа #'.$order->id)

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<div class="order-details-wrapper">
    <div class="container py-5 bg-light">
        <div class="order-details mx-auto p-4 shadow rounded-4 bg-white" style="max-width: 1200px;">
            <!-- Заголовок заказа -->
                <div class="order-header animate-fade">
                    <h4>Детали заказа #{{ $order->id }}</h4>
                    <span class="order-status status-{{ $order->status }}">
                        {{ $order->status }}
                    </span>
                    @php
                        $statuses = ['новый', 'в обработке', 'отправлен', 'доставлен', 'отменён'];
                    @endphp

                    @if(auth()->user()?->is_admin) {{-- Проверка на админа --}}
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PATCH')
                            <label for="status">Сменить статус:</label>
                            <select name="status" id="status" class="form-select d-inline-block w-auto mx-2">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @if($order->status === $status) selected @endif>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Обновить</button>
                        </form>
                    @endif
                </div>

                <!-- Основная информация -->
                <div class="info-grid">
                    <!-- Информация о клиенте -->
                    <div class="info-card animate-fade">
                        <div class="card-header">
                            <i class="fas fa-user"></i>
                            <h5 class="mb-0">Информация о клиенте</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <span class="info-label">Имя</span>
                                <span class="info-value">{{ $order->user->name ?? 'Не указано' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ $order->user->email ?? 'Не указан' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о заказе -->
                    <div class="info-card animate-fade">
                        <div class="card-header">
                            <i class="fas fa-receipt"></i>
                            <h5 class="mb-0">Информация о заказе</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <span class="info-label">Дата создания</span>
                                <span class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Сумма заказа</span>
                                <span class="info-value price">{{ number_format($order->total_amount, 2) }} ₽</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Адрес доставки</span>
                                <span class="info-value">{{ $order->address_data['address'] ?? 'Не указан' }}</span>
                            </div>

                            @if($order->comment)
                                <div class="card shadow-sm animate-fade mt-3">
                                    <div class="card-header bg-white border-bottom">
                                        <h5 class="mb-0">Комментарий клиента</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $order->comment }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            <!-- Товары -->
            <div class="card products-card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Товары в заказе</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table products-table mb-0">
                            <thead>
                                <tr>
                                    <th>Модель</th>
                                    <th>Цвет</th>
                                    <th>Материал</th>
                                    <th>Размеры</th>
                                    <th class="text-center">Кол-во</th>
                                    <th class="text-end">Цена</th>
                                    <th class="text-end">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td data-label="Модель">
                                            @php
                                                $types = [
                                                    'tshirt' => 'Футболка',
                                                    'hoodie' => 'Худи',
                                                    'dress' => 'Платье',
                                                    'suit' => 'Костюм',
                                                ];
                                                $typeKey = $order->model_type ?? 'tshirt';
                                            @endphp
                                            {{ $types[$typeKey] ?? 'Неизвестно' }}
                                        </td>
                                        <td data-label="Цвет">
                                            <div class="d-flex align-items-center">
                                                <span class="color-badge" style="background-color: {{ $item->color ?? $order->color }}"></span>
                                                {{ $item->color_name ?? $order->color }}
                                            </div>
                                        </td>
                                        <td data-label="Материал">{{ $item->material ?? $order->material }}</td>
                                        <td data-label="Размеры">
                                            <div class="size-values">
                                                @foreach($item->size_data ?? $order->size_data as $key => $value)
                                                    @if(is_numeric($value))
                                                        <div class="size-item">
                                                            <span>{{ ucfirst($key) }}:</span>
                                                            <strong>{{ $value }} см</strong>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center" data-label="Кол-во">{{ $item->quantity }}</td>
                                        <td class="text-end" data-label="Цена">{{ number_format($item->total_amount, 2) }} ₽</td>
                                        <td class="text-end" data-label="Сумма">{{ number_format($item->total_amount * $item->quantity, 2) }} ₽</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@push('scripts')
    <script>
        // Дополнительные скрипты, если нужны
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Order details page loaded');
        });
    </script>
@endpush