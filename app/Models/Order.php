<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model_type',
        'color',
        'material',
        'payment_method',
        'status',
        'size_data',
        'address_data',
        'total_amount',
        'comment'
    ];

    protected $casts = [
        'size_data' => 'array',
        'address_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Текстовые представления
    public function getModelTypeTextAttribute()
    {
        $types = [
            'tshirt' => 'Футболка',
            'hoodie' => 'Толстовка',
            'dress' => 'Платье',
            'suit' => 'Костюм'
        ];
        return $types[$this->model_type] ?? $this->model_type;
    }

    public function getMaterialTextAttribute()
    {
        $materials = [
            'cotton' => 'Хлопок',
            'polyester' => 'Полиэстер',
            'wool' => 'Шерсть'
        ];
        return $materials[$this->material] ?? $this->material;
    }

    public function getPaymentMethodTextAttribute()
    {
        $methods = [
            'sbp' => 'СБП',
            'card' => 'Карта'
        ];
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}