<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function index()
    {
        return view('payment.gmo.index');
    }

    public function creditCard()
    {
        $dateTo = request('date_to', now()->subMonth(3)->format('Y-m-d'));
        $dateFrom = request('date_from', date('Y-m-d'));

        $orders = Order::when(Gate::denies('admin'), function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereDate('created_at', '>=', $dateTo)
        ->whereDate('created_at', '<=', $dateFrom)
        ->get();

        return view('payment.gmo.credit-cards.index', compact('orders', 'dateTo', 'dateFrom'));
    }

    public function paypay()
    {
        $dateTo = request('date_to', date('Y-m-d'));
        $dateFrom = request('date_from', date('Y-m-d'));

        $orders = Order::when(Gate::denies('admin'), function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereDate('created_at', '>=', $dateTo)
        ->whereDate('created_at', '<=', $dateFrom)
        ->get();

        return view('payment.gmo.credit-cards.index', compact('orders'));
    }

    public function resultPayPay(Request $request)
    {
        dd($request->all());
    }
}
