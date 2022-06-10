<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->get();
        
        return view('payment.gmo.index', compact('orders'));
    }
}
