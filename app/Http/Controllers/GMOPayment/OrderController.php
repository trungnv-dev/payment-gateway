<?php

namespace App\Http\Controllers\GMOPayment;

use App\Enums\GMOPayment;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Ecs\GmoPG\Services\CreditCardService;
use Ecs\GmoPG\Services\MemberCardService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $products = Product::simplePaginate(5);

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
                'status'       => GMOPayment::TRAN_STT_UNPAID,
                'job_cd'       => $request->job_cd ? GMOPayment::JOB_CD_AUTH : GMOPayment::JOB_CD_CAPTURE,
                'secure'       => $request->td_flag,
            ]);

            $orderId = generate_order_id($order->id);

            $transaction = resolve(CreditCardService::class)
                ->entryTran([
                    'JobCd'    => $order->job_cd,
                    'OrderID'  => $orderId,
                    'Amount'   => $order->total_charge,
                    'TdFlag'   => $order->secure,
                    'Tds2Type' => 3, // Normal authorization when 3DS 2.0 is not supported
                ]);

            if (isset($transaction['ErrInfo'])) {
                return redirect()->back()->withErrors($transaction['errors']);
            }

            $order->access_id   = $transaction['AccessID'];
            $order->access_pass = $transaction['AccessPass'];
            $order->order_id    = $orderId;
            $order->save();

            $order->products()->sync($request->product);

            DB::commit();

            return redirect()->route('payment.gmo.credit_card')->withMessage('Create Order Success!');
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Order $order)
    {
        $transaction = resolve(CreditCardService::class)
            ->searchTrade([
                'OrderID' => $order->order_id
            ]);

        abort_if(isset($transaction['errors']), Response::HTTP_NOT_FOUND);

        $order->load('products');

        $cards = (auth()->user()->gmo_member_id && in_array($transaction['Status'], GMOPayment::STATUS_UNPAID))
                    ? $cards = resolve(MemberCardService::class)
                        ->searchCard([
                            'MemberID' => auth()->user()->gmo_member_id
                        ])
                    : [];

        return view('payment.gmo.orders.show', compact('transaction', 'order', 'cards'));
    }

    public function execTran(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'AccessID'   => $order->access_id,
                'AccessPass' => $order->access_pass,
                'OrderID'    => $order->order_id,
                'Method'     => GMOPayment::EXEC_BULK,
            ];

            if ($request->type_pg == 1) {
                $data['CardNo'] = $request->cardNumber;
                $data['Expire'] = $request->expire;
            } else {
                $data['MemberID'] = auth()->user()->gmo_member_id;
                $data['CardSeq']  = $request->card_seq;
            }

            if ($order->secure == 2) {
                $data['RetUrl'] = route('payment.gmo.order.secureTran', ['order' => $order->id, 'type' => 2]);
            }

            $transaction = resolve(CreditCardService::class)->execTran($data);

            if (isset($transaction['ErrInfo'])) {
                return redirect()->back()->withErrors($transaction['errors']);
            }

            if (isset($transaction['ACS']) && $transaction['ACS'] == 1) {
                $transaction['orderId'] = $order->id;

                return view('payment.gmo.orders.secure', compact('transaction'));
            } elseif (isset($transaction['ACS']) && $transaction['ACS'] == 2) {
                $transaction['orderId'] = $order->id;

                return view('payment.gmo.orders.secure_3ds2', compact('transaction'));
            }

            $order->status = GMOPayment::TRAN_STT_PAID;
            $order->save();

            DB::commit();

            return redirect()->route('payment.gmo.order.show', ['order' => $order->id])->withMessage('Pay Order Success!');
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function secure()
    {
        return view('payment.gmo.orders.secure');
    }

    public function secureTran(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            if (request('type') == 2) {
                $data = [
                    'AccessID'   => $request['AccessID'],
                    'AccessPass' => $order->access_pass,
                ];

                $transaction = resolve(CreditCardService::class)->secureTran2($data);
            } else {
                $data = [
                    'MD'      => $request['MD'],
                    'PaRes'   => $request['PaRes'],
                ];

                // $transaction = CreditCardPaymentService::secureTran($data);
            }

            if (isset($transaction['ErrInfo'])) {
                return redirect()->route('payment.gmo.order.show', ['order' => $order->id])->withErrors($transaction['errors']);
            }

            $order->status = GMOPayment::TRAN_STT_PAID;
            $order->save();

            DB::commit();

            return redirect()->route('payment.gmo.order.show', ['order' => $order->id])->withMessage('Pay Order Success!');
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function alterTran(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            $transaction = resolve(CreditCardService::class)
                ->alterTran([
                    'JobCd'      => $request->cancel ? GMOPayment::JOB_CD_CANCEL : GMOPayment::JOB_CD_SALES,
                    'AccessID'   => $order->access_id,
                    'AccessPass' => $order->access_pass,
                    'Amount'     => $order->total_charge,
                ]);

            if (isset($transaction['ErrInfo'])) {
                return redirect()->back()->withErrors($transaction['errors']);
            }

            $order->status = $request->cancel ? GMOPayment::TRAN_STT_UNPAID : GMOPayment::TRAN_STT_PAID;
            $order->job_cd = $request->cancel ? GMOPayment::JOB_CD_CANCEL : GMOPayment::JOB_CD_SALES;
            $order->save();

            DB::commit();

            return redirect()->route('payment.gmo.order.show', ['order' => $order->id])->withMessage($request->cancel ? 'Cancel Order Success!' : 'Sales Order Success!');
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollBack();

            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
