<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'height' => 'required|numeric',
            'chest' => 'required|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric'
        ]);

        $size = auth()->user()->sizes()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return back()->with('success', 'Размеры успешно сохранены!');
    }
}