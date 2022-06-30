<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\CreditCardPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $products = Product::all();

        return view('payment.gmo.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $amount = Product::selectRaw("SUM(price) AS total")->whereIn('id', $request->product)->first()->total;

            $order = Order::create([
                'user_id'      => auth()->id(),
                'total_charge' => $amount,
                'status'       => 0,
            ]);

            $transaction = CreditCardPaymentService::entryTran($order->id, $amount);

            if (isset($transaction['AccessID'])) {
                $order->access_id   = $transaction['AccessID'];
                $order->access_pass = $transaction['AccessPass'];
                $order->save();

                $order->products()->sync($request->product);

                DB::commit();

                return redirect()->route('payment.gmo.index')->withMessage('Create Order Success!');
            }

            DB::rollBack();
            
            return redirect()->back()->withErrors(explode('|', $transaction['ErrInfo']));
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(500);
        }
    }

    public function show(Order $order)
    {
        $order->load('products');

        // dd($order);
        
        return view('payment.gmo.orders.show', compact('order'));
    }

    public function execTran(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'AccessID'   => $order->access_id,
                'AccessPass' => $order->access_pass,
                'OrderID'    => $order->id,
                'MemberID'   => auth()->user()->gmo_member_id,
                'CardSeq'    => 0,
            ];

            $transaction = CreditCardPaymentService::execTran($data);

            if (isset($transaction['ErrInfo'])) {
                return redirect()->back()->withErrors(explode('|', $transaction['ErrInfo']));
            }

            $order->status = 1;
            $order->save();

            DB::commit();

            return redirect()->route('payment.gmo.order.show', ['order' => $order->id])->withMessage('Pay Order Success!');
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(500);
        }
    }
}
