<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CardService;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function create()
    {
        abort_unless(auth()->user()->gmo_member_id, 404);

        return view('payment.gmo.cards.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->gmo_member_id, 404);

        try {
            $response = CardService::saveCard($user->gmo_member_id, $request->card_number, $request->card_expire);

            if (isset($response['CardSeq'])) {
                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Regist Card Success!');
            }
            
            return redirect()->back()->withErrors(explode('|', $response['ErrInfo']))->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            abort(500);
        }
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->gmo_member_id, 404);

        $response = CardService::deleteCard($user->gmo_member_id, $request->card_seq);

        if (isset($response['CardSeq'])) {
            return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Delete Card Success!');
        }

        return redirect()->back()->withErrors(explode('|', $response['ErrInfo']));
    }
}
