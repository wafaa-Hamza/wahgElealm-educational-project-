<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SubscriptionCodeNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class LoginController extends Controller
{

    public function register(Request $request)
    {

        try {

            $validateUser = Validator::make($request->all(), [
                'name' => 'required|max:20',
                'phonenumber' => 'numeric',
                'email'=> 'email',
                'password' => 'min:6',
                'password_confirmation' => 'min:6',
                'date_of_birth' => 'date',
                'gender' => 'in:male,female',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }


            $user = User::create([
                'name' => $request->name,
                'phonenumber' => $request->phonenumber,
                'password' =>  Hash::make($request->password),
                'password_confirmation' => Hash::make($request->password_confirmation),
                'email' =>$request->email,
                'date_of_birth' =>$request->date_of_birth,
                'gender' => $request->gender,
            ]);


            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request){

        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',

        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors(),
            ], 400);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $user->api_token = Str::random(60);
            $user->save();


            return response()->json([
                'status' => true,
                'message' => 'Account Logged In Successfully',
                ' $user ' => $user->api_token,
                'token' => $user,

            ], 200);
        }


        return response()->json([
            'status' => false,
            'message' => 'Invalid email, password, or role',
        ], 401);

}


///////////////////////////////// student and instructor auth ///////////////////////////////////////////////


public function register_for_Stu_Inst(Request $request)
  {

            $validateUser = Validator::make($request->all(), [
                    'name' => 'required|max:20',
                    'phonenumber' => 'numeric',
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|min:6',
                    'date_of_birth' => 'date',
                    'gender' => 'in:male,female',
                    'user_type' => 'required|in:student,instructor',
                ]);

                if ($validateUser->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation Error',
                        'errors' => $validateUser->errors(),
                    ], 401);
                }


                      $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->phonenumber = $request->phonenumber;
                    $user->gender = $request->gender;
                    $user->date_of_birth = $request->date_of_birth;
                    $user->password_confirmation = bcrypt($request->password_confirmation);
                    $user->password = bcrypt($request->password);
                    $user->user_type = $request->user_type;
                    $user->api_token = Str::random(60);
                    $user->save();

                    Auth::login($user);

                    if ($user->user_type == 'instructor') {
                        return response()->json(['redirect' => url('instructor/dashboard')], 200);
                    } elseif ($user->user_type == 'student') {
                        return response()->json(['redirect' => url('student/dashboard')], 200);
                    } else {
                        return response()->json(['error' => 'Invalid user type'], 400);
                    }


// return response()->json([
//     'status' => true,
//     'message' => 'Account Registered Successfully',
//     'token' => $user->api_token,
//     'user_type' => $user->user_type, // Return user role
//     'user'=>$user
// ], 200);
}


    public function Login_for_Stu_Inst(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
           'user_type' => 'required|in:student,instructor',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors(),
            ], 400);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'user_type' => $request->user_type])) {

            $user = Auth::user();

        //  return   $user;
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Account Logged In Successfully',
            'token' => $user->api_token,
            'user_type' => $user->user_type, // Return user role
            'user' => $user, // Return user role
        ], 200);
    }


    return response()->json([
        'status' => false,
        'message' => 'Invalid email, password, or role',
    ], 401);

}




    //         if ($user->user_type == 'instructor') {
    //             return response()->json(['redirect' => url('instructor/dashboard')], 200);

    //         } elseif ($user->user_type == 'student') {
    //             return response()->json(['redirect' => url('student/dashboard')], 200);
    //         } else {
    //             return response()->json(['error' => 'Invalid user type'], 400);
    //         }
    //     } else {
    //         return response()->json(['error' => 'Invalid credentials'], 401);
    //     }
    // }
}



