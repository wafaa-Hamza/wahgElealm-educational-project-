<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartForStudent;
use App\Models\CoursesOfInstructor;
use App\Models\Order;
use Illuminate\Http\Request;

class CartForStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $cartItems = CartForStudent::where('user_id', auth()->id())->with('courses')->get();

    return response()->json([
        'cartItems' => $cartItems,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */


    public function addCourse(Request $request)
    {
        $courseId=$request->courseId;

        $course = CoursesOfInstructor::find($courseId);
        if (!$course) {
            return response()->json(['error', 'Course not found!']);
        }

        $cart = auth()->user()->cartStudent;
        if (!$cart) {
            $cart = auth()->user()->cartStudent()->create();
        }

        $cartCourse = $cart->courses()->where('courses_of_instructor_id', $courseId)->first();
        if ($cartCourse) {
            $cartCourse->pivot->quantity++;
            $cartCourse->pivot->save();
        } else {
            $cart->courses()->attach($courseId, ['quantity' => 1]);
        }

        return response()->json(['message', 'Course added to cart successfully.']);
    }

    public function checkout()
    {
        $cart = auth()->user()->cartStudent;
        if (!$cart) {
            return response()->json(['message', 'Cart not found!']);
        }

    }

    public function removeCourse(Request $request)
    {
        $courseId=$request->courseId;

        $cart = auth()->user()->cartStudent;
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
        $cart = auth()->user()->cartStudent;
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




    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
