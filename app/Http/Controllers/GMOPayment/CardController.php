<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ecs\GmoPG\Services\MemberCardService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function create(Request $request)
    {
        abort_unless(auth()->user()->gmo_member_id, Response::HTTP_NOT_FOUND);

        if ($request->type == 1) {
            return view('payment.gmo.credit-cards.cards.create_token');
        }

        return view('payment.gmo.credit-cards.cards.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        try {
            $response = resolve(MemberCardService::class)
                ->saveCard([
                    'MemberID' => $user->gmo_member_id,
                    'CardNo'   => $request->card_number,
                    'Expire'   => $request->card_expire,
                    // 'Token'   => "e5e770af1226dacf75eaebb83a3bd4aa9f4c5853c1761ba9f0eab921e7a29b27",
                ]);

            if (isset($response['CardSeq'])) {
                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Regist Card Success!');
            }

            return redirect()->back()->withErrors($response['errors'])->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        $response = resolve(MemberCardService::class)
            ->deleteCard([
                'MemberID' => $user->gmo_member_id,
                'CardSeq'  => $request->card_seq,
            ]);

        if (isset($response['CardSeq'])) {
            return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Delete Card Success!');
        }

        return redirect()->back()->withErrors(explode('|', $response['ErrInfo']));
    }
}
