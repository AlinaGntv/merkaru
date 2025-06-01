<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Size;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\FeedbackMessage;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Создает новый заказ
     */
    public function create(Request $request)
    {
        try {

            $request->merge([
                'items' => json_decode($request->input('items'), true)
            ]);

            $validated = $request->validate([
                'model_type' => 'required|in:tshirt,hoodie,dress,suit',
                'color' => 'required|string',
                'material' => 'required|string',
                'address' => 'required|string',
                'height' => 'required|numeric',
                'chest' => 'required|numeric',
                'waist' => 'required|numeric',
                'hips' => 'required|numeric',
                'shoulder_width' => 'required|numeric',
                'sleeve_length' => 'required|numeric',
                'neck_circumference' => 'required|numeric',
                'wrist_circumference' => 'required|numeric',
                'thigh_circumference' => 'required|numeric',
                'knee_circumference' => 'required|numeric',
                'inseam_length' => 'required|numeric',
                'comment' => 'nullable|string|max:1000',

                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0'
            ]);

            $sizeData = $request->only([
                'height', 'chest', 'waist', 'hips',
                'shoulder_width', 'sleeve_length',
                'neck_circumference', 'wrist_circumference',
                'thigh_circumference', 'knee_circumference',
                'inseam_length'
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'model_type' => $validated['model_type'],
                'color' => $validated['color'],
                'material' => $validated['material'],
                'status' => 'new',
                'size_data' => $sizeData,
                'address_data' => ['address' => $validated['address']],
                'total_amount' => $total,
                'comment' => $validated['comment'] ?? null,
            ]);

            if (!empty($request->comment)) {
                FeedbackMessage::create([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'message' => $request->comment,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id 
                ]);
            }

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'total_amount' => $item['price'] * $item['quantity'] 
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно создан! Номер заказа: #'.$order->id,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании заказа: '.$e->getMessage()
            ], 500);
        }
    }

    private function calculateOrderTotal($modelType, $material)
    {
        $basePrices = [
            'tshirt' => 3500,
            'hoodie' => 7000,
            'dress' => 5000,
            'suit' => 10000,
        ];

        $materialModifiers = [
            'cotton' => 1.0,
            'polyester' => 0.9,
            'wool' => 1.2,
        ];

        $base = $basePrices[$modelType] ?? 0;
        $modifier = $materialModifiers[$material] ?? 1;

        return (int)($base * $modifier);
    }

    /**
     * Получает список заказов пользователя
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->get();
        $showSuccess = $request->has('order_success');
        
        return view('dashboard', [
            'orders' => $orders,
            'showSuccess' => $showSuccess
        ]);
    }

    // OrderController.php

    public function dashboard()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->get();
        
        return view('dashboard', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']); 
        return view('admin.orders.show', compact('order'));
    }

}