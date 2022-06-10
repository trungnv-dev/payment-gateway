<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function paypal()
    {
        return view('payment.paypal');
    }

    public function payjp()
    {
        $user = auth()->user();
        $cardList = [];

        // Check xem user này đã thanh toán lần đầu chưa, nếu rồi thì sẽ get list card đã thanh toán trước đó ra để user có thể chọn mà ko cần input lại
        if (!empty($user->payjp_customer_id)) {
            // Set secret key của shop
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));

            // Get list card của customer dựa vào payjp_customer_id
            $cardDatas = \Payjp\Customer::retrieve($user->payjp_customer_id)->cards->data;

            foreach ($cardDatas as $cardData) {
                $cardList[] = [
                    'id'         => $cardData->id,
                    'cardNumber' => "**** **** **** {$cardData->last4}",
                    'brand'      => $cardData->brand,
                    'exp_year'   => $cardData->exp_year,
                    'exp_month'  => $cardData->exp_month,
                    'name'       => $cardData->name,
                ];
            }
        }

        return view('payment.payjp', compact('cardList'));
    }

    public function paymentPayjp(Request $request)
    {
        abort_if(empty($request->get('payjp-token')) && !$request->get('payjp_card_id'), 404);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Set secret key của shop
            \Payjp\Payjp::setApiKey(config('payjp.secret_key'));

            //  Kiểm tra xem user hiện tại đang chọn card nào (áp dụng vs user thanh toán từ lần thứ 2)
            if (!empty($request->get('payjp_card_id'))) {
                // get thông tin customer
                $customer = \Payjp\Customer::retrieve($user['payjp_customer_id']);

                // set default_card = card đã chọn
                $customer->default_card = $request->get('payjp_card_id');
                $customer->save();

            }
            // Trường hợp user ko chọn card có sẵn, mà nhập card khác (áp dụng vs user thanh toán từ lần thứ 2)
            elseif (!empty($user['payjp_customer_id'])) {
                // get thông tin customer
                $customer = \Payjp\Customer::retrieve($user['payjp_customer_id']);

                // tạo card mới cho customer
                $card = $customer->cards->create([
                    'card' => $request->get('payjp-token'),
                ]);

                // set default_card = card đã chọn
                $customer->default_card = $card->id;
                $customer->save();
            }
            // Trường hợp user thanh toán lần đầu
            else {
                // Tạo 1 customer của user hiện tại vào shop, với thông tin thẻ được tạo trước đó, dựa vào payjp-token để get ra
                $customer = \Payjp\Customer::create([
                    'card' => $request->get('payjp-token'),
                    'description' => "userId: {$user->id}, userName: {$user->name}",
                ]);

                // Lưu lại id của customer này, để sau này dựa vào đây để biết được user đã từng thanh toán chưa
                $user->payjp_customer_id = $customer->id;
                $user->save();
            }

            // Xử lý thanh toán cho customer đó
            \Payjp\Charge::create([
                "customer" => $customer->id,
                "amount" => $request->price,
                "currency" => 'jpy',
            ]);

            DB::commit();

            return redirect()->back()->with('message', 'Charge Success!');
        } catch (\Exception $e) {
            // T/h charge xảy ra lỗi, ghi log lỗi cụ thể và trả lỗi về cho user
            Log::channel('payment')->error($e->getMessage());
            DB::rollback();

            return redirect()->back()->with('error', $e->getMessage())->withInput($request->all());
        }
    }
}
