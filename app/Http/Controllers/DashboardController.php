<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $orders = auth()->user()->orders;
        $showSuccess = session('showSuccess', false); 

        return view('dashboard', compact('orders', 'showSuccess'));
    }

}
