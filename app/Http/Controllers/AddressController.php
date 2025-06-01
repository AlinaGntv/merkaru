<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Сохраняет адрес пользователя
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100'
        ]);

        $address = Address::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Адрес успешно сохранен!',
            'data' => $address
        ]);
    }

    /**
     * Получает текущий адрес пользователя
     */
    public function show()
    {
        $address = Auth::user()->address;

        return response()->json([
            'success' => true,
            'data' => $address ?? null
        ]);
    }
}