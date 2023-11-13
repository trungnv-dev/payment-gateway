<?php

namespace App\Http\Controllers\GMOPayment;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Ecs\GmoPG\Services\MemberCardService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function create()
    {
        abort_if(auth()->user()->gmo_member_id, Response::HTTP_NOT_FOUND);

        return view('payment.gmo.members.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_if($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        DB::beginTransaction();

        try {
            $memberId = generate_member_id($user->id);

            $response = resolve(MemberCardService::class)
                ->saveMember([
                    'MemberID'   => $memberId,
                    'MemberName' => $request->member_name,
                ]);

            if (isset($response['MemberID'])) {
                $user->gmo_member_id = $response['MemberID'];
                $user->save();
                DB::commit();

                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Regist Member Success!');
            }

            return redirect()->back()->withErrors($response['errors'])->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            DB::rollback();
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        $member = resolve(MemberCardService::class)
            ->searchMember([
                'MemberID' => $user->gmo_member_id
            ]);

        if (isset($member['MemberID'])) {
            $cards = resolve(MemberCardService::class)
                ->searchCard([
                    'MemberID' => $user->gmo_member_id
                ]);

            return view('payment.gmo.members.show', compact('member', 'cards'));
        }

        return redirect()->back()->withErrors($member['errors']);
    }

    public function edit(User $user)
    {
        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        $response = resolve(MemberCardService::class)
            ->searchMember([
                'MemberID' => $user->gmo_member_id
            ]);

        if (isset($response['MemberID'])) {
            return view('payment.gmo.members.edit', ['member' => $response]);
        }

        return redirect()->back()->withErrors($response['errors']);
    }

    public function update(User $user, Request $request)
    {
        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        try {
            $response = resolve(MemberCardService::class)
                ->updateMember([
                    'MemberID'   => $user->gmo_member_id,
                    'MemberName' => $request->member_name
                ]);

            if (isset($response['MemberID'])) {
                return redirect()->route('payment.gmo.member.show', ['user' => $user->id])->withMessage('Update Member Success!');
            }

            return redirect()->back()->withErrors($response['errors'])->withInput($request->all());
        } catch (\Exception $e) {
            Log::channel('payment')->error($e->getMessage());
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {
        abort_unless($user->gmo_member_id, Response::HTTP_NOT_FOUND);

        $response = resolve(MemberCardService::class)
            ->deleteMember([
                'MemberID' => $user->gmo_member_id
            ]);

        if (isset($response['MemberID'])) {
            $user->gmo_member_id = NULL;
            $user->save();

            return redirect()->route('payment.gmo.index')->withMessage('Delete Member Success!');
        }

        return redirect()->back()->withErrors($response['errors']);
    }
}
