<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Services\CardService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function create()
    {
        abort_if(auth()->user()->gmo_member_id, 404);

        return view('payment.gmo.members.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_if($user->gmo_member_id, 404);

        DB::beginTransaction();
        try {
            $memberId = 'mem_' . $user->id . '_' . \Str::uuid()->getHex();

            $response = MemberService::saveMember($memberId, $request->member_name);

            if (isset($response['MemberID'])) {
                $user->gmo_member_id = $response['MemberID'];
                $user->save();
                DB::commit();

                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Regist Member Success!');
            }
            
            return redirect()->back()->withErrors(explode('|', $response['ErrInfo']))->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollback();
            abort(500);
        }
    }

    public function show(User $user)
    {
        abort_unless($user->gmo_member_id, 404);

        $member = MemberService::searchMember($user->gmo_member_id);

        $cards = CardService::searchCard($user->gmo_member_id);

        if (isset($member['MemberID'])) {
            return view('payment.gmo.members.show', compact('member', 'cards'));
        }

        return redirect()->back()->withErrors(explode('|', $member['ErrInfo']));
    }

    public function edit(User $user)
    {
        abort_unless($user->gmo_member_id, 404);

        $response = MemberService::searchMember($user->gmo_member_id);

        if (isset($response['MemberID'])) {
            return view('payment.gmo.members.edit', ['member' => $response]);
        }

        return redirect()->back()->withErrors(explode('|', $response['ErrInfo']));
    }

    public function update(User $user, Request $request)
    {
        abort_unless($user->gmo_member_id, 404);

        try {
            $response = MemberService::updateMember(auth()->user()->gmo_member_id, $request->member_name);

            if (isset($response['MemberID'])) {
                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Update Member Success!');
            }
            
            return redirect()->back()->withErrors(explode('|', $response['ErrInfo']))->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            abort(500);
        }
    }

    public function destroy(User $user)
    {
        abort_unless($user->gmo_member_id, 404);

        $response = MemberService::deleteMember($user->gmo_member_id);

        if (isset($response['MemberID'])) {
            $user->gmo_member_id = NULL;
            $user->save();

            return redirect()->route('payment.gmo.index')->withMessage('Delete Member Success!');
        }

        return redirect()->back()->withErrors(explode('|', $response['ErrInfo']));
    }
}
