@extends('layouts.admin')

@section('content')
<div class="container py-4 animate-fade">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Сообщения обратной связи</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Назад в админпанель
        </a>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Имя и Email</th>
                            <th>Дата</th>
                            <th>Сообщение</th>
                            <th>Пользователь</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr class="hover-bg">
                            <td>
                                <strong class="text-primary">{{ $message->name }}</strong>
                                <div class="text-muted small">{{ $message->email }}</div>
                            </td>
                            <td class="text-nowrap">
                                {{ $message->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td>
                                {{ Str::limit($message->message, 150) }}
                            </td>
                            <td>
                                @if($message->user)
                                    <span class="badge bg-info text-white">
                                        <i class="fas fa-user"></i> {{ $message->user->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.feedback.show', $message) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Подробнее
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection