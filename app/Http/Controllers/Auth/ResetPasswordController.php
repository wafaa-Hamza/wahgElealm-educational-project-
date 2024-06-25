<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }


    // protected function resetPassword($user, $password)
    // {
    //     $user->password = bcrypt($password);
    //     $user->setRememberToken(Str::random(60));
    //     $user->save();

    //     $this->guard()->login($user);
    // }

    public function showResetForm(Request $request, $token = null)
    {
       return response()->json( ['token' => $token, 'email' => $request->email]);
    }
}
