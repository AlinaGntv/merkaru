@extends('layouts.admin')

@section('content')
<div class="container py-4 animate-fade">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Сообщение #{{ $message->id }}</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Назад в админпанель
        </a>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th width="20%" class="bg-light">Имя</th>
                            <td>{{ $message->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td>{{ $message->email }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Дата отправки</th>
                            <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="bg-light">Сообщение</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $message->message }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('admin.feedback') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> К списку сообщений
                </a>
                <form action="{{ route('admin.feedback.delete', $message) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Удалить
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection