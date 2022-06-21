<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();

            // remove all access_token of user this
            $user->tokens()->delete();

            // Create new a token
            $token = $user->createToken('Password-Grant-Client');

            // Update time expires token
            $token->token->update(['expires_at' => now()->addMinutes(10)]);
            
            return [
                'token_type'   => 'Bearer',
                'access_token' => $token->accessToken,
            ];
        }
    }

    public function user(User $user)
    {
        return $user;
    }
}
