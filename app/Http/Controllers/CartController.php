<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $cartItems = Cart::with('courses')->get();

          return($cartItems);

}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try{
            $request->validate([
                'user_id'     => 'required|unique:carts|max:254',
                'course_id' => 'required|unique:carts|max:254',
                'quantity' => 'required|integer',

            ]);

            $CartItem = Cart::create([
                'user_id'=>$request->user_id,
                'course_id'=>$request->course_id,
                'quantity'=>$request->quantity,

            ]);
            return response()->json([
                'message' => 'CartItem Created Successfully',
                'data'    =>  $CartItem ,
            ], 201);

        }catch (ValidationException $e) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Cart::with('courses')->find($id);
        return $cart;
    }

    /**
     * Update the specified resource in storage.
     */
//     public function update(Request $request,$id)
//     {
// //dd($id);
//         $request->validate([
//             'user_id' => 'max:254',

//         ]);
//         $cartItem = Cart::find($id);
//         //dd($cartItem);
//         if (!$cartItem) {
//             return response()->json([
//                 'message' => 'CartItem not found',
//             ], 404);
//         }

//         $cartItem->update([
//             'user_id' => $request->user_id ?? $cartItem->user_id,
//             'course_id' => $request->course_id ?? $cartItem->course_id,
//             'quantity' => $request->quantity ?? $cartItem->quantity,
//         ]);

//         return response()->json([
//             'message' => 'CartItem Updated Successfully',
//             'data' => $cartItem,
//         ], 200);
//     }
public function addToCart(Request $request)
{

    $courseId=$request->courseId;
    $course = Course::find($courseId);
    if(!$course) {
          return response()->json(['message' => 'Course not found!']);
    }

    $cart = auth()->user()->cart;
    if(!$cart) {
        $cart = auth()->user()->cart()->create(['discount' => 10]);
    }

    $cartCourse = $cart->courses()->where('course_id', $courseId)->first();
  //  return  $cartCourse;
    if($cartCourse) {
     $cartCourse->pivot->quantity++;
        $cartCourse->pivot->save();
    } else {
        $cart->courses()->attach($courseId, ['quantity' => 1]);
    }

    return response()->json([
          'message' => 'CartItem Added Successfully']);
}



public function viewCart()
    {
        $cart = auth()->user()->cart;
        if (!$cart) {
            return response()->json([
                'cartItems' => [],
                'totalPrice' => 0,
                'discountedPrice' => 0,
            ]);
        }

        $cartItems = $cart->courses()->with('teacher','teacher.RatingCourse')->withPivot('quantity')->get();
        $totalPrice = $cartItems->sum(function($course) {
            return $course->price * $course->pivot->quantity;
        });
    $no_of_courses=$cart->courses()->count();
        $discount = $cart->discount;
    $discountedPrice = $totalPrice - ($totalPrice * ($discount / 100)); //after discount

        return response()->json([
            'no_of_courses' => $no_of_courses,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'discountedPrice' => $discountedPrice,
        ]);
    }

    public function removeCourse( Request $request)
    {
        $courseId=$request->courseId;

        $cart = auth()->user()->cart;

        if ($cart) {
            $cart->courses()->detach($courseId);

            return response()->json([
                'message' => 'Course removed from cart successfully!'
            ]);
        }

        return response()->json([
            'message' => 'Cart not found!'
        ], 404);
    }


    public function removeCart()
    {
        $cart = auth()->user()->cart;

        if ($cart) {
            $cart->courses()->detach();
            $cart->delete();

            return response()->json([
                'message' => 'Cart removed successfully!'
            ]);
        }

        return response()->json([
            'message' => 'Cart not found!'
        ], 404);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
    //
}

}
