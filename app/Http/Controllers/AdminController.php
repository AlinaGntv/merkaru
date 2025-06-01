<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Size;
use App\Models\Message;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'orders' => Order::with(['user', 'items'])->latest()->take(5)->get(),
            'sizes' => Size::with('user')->latest()->take(5)->get(),
            'messages' => Message::latest()->take(5)->get(),
            'feedbackMessages' => FeedbackMessage::with('user')->latest()->take(5)->get() 
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function orders()
    {
        $orders = Order::with(['user', 'items'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product']); // Грузим связанные данные
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:новый,в обработке,отправлен,доставлен,отменён'],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Статус заказа обновлён.');
    }

    public function feedback()
    {
        $messages = FeedbackMessage::with('user')->latest()->get();
        return view('admin.feedback.index', compact('messages')); 
    }

    public function showFeedback(FeedbackMessage $message)
    {
        return view('admin.feedback.show', compact('message'));
    }

    public function deleteFeedback(FeedbackMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.feedback')->with('success', 'Сообщение удалено');
    }
}