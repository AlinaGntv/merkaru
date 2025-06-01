<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Футболка',
                'description' => 'Классическая футболка из натурального хлопка. Доступна в различных цветах и стилях.',
                'image' => 'product1.png'
            ],
            [
                'id' => 2,
                'name' => 'Толстовка',
                'description' => 'Уютная толстовка для прохладных дней. Выберите свой любимый материал и цвет.',
                'image' => 'product2.png'
            ],
            [
                'id' => 3,
                'name' => 'Платье',
                'description' => 'Элегантное платье, идеально подчеркивающее вашу фигуру. Индивидуальная посадка гарантирована.',
                'image' => 'product3.png'
            ],
            [
                'id' => 4,
                'name' => 'Костюм',
                'description' => 'Деловой костюм для особых случаев. Идеально подходит для работы и официальных мероприятий.',
                'image' => 'product4.png'
            ]
        ];

        return view('catalog', compact('products'));
    }

    public function order($productId)
    {
        // Редирект на страницу заказа с предзаполненным товаром
        return redirect()->route('dashboard')->with('selected_product', $productId);
    }
}