<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|min:8|max:64',
        ]);

        $remember = $request->validate([
            'remember' => 'required|boolean',
        ])['remember'];

        if (Auth::attempt($credentials, $remember)) {

            return response([
                'user' => Auth::user(),
            ], 200);
        }

        return response([
            'message' => 'wrong email or password'
        ], 422);

    }

    public function logout()
    {
        Auth::logout();

        return response([], 204);
    }

    public function postmanToken()
    {
        $user = User::first();
        $user->tokens()->delete();
        return response(['token' => $user->createToken('postman')->plainTextToken], 200);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mail.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return 'We have e-mailed your password reset link!';
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $token = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$token) return response('Invalid token!', 404);

        //TODO add expiration of token

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return 'your password successfully updated!';
    }

}
