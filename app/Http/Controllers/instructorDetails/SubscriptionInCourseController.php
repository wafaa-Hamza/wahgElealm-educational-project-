<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionInCourse;
use Illuminate\Http\Request;

class SubscriptionInCourseController extends Controller
{
    public function verify(Request $request)
    {
        $code = $request->input('code');
        $subscription = SubscriptionInCourse::where('subscription_code', $code)->where('status', 'paid')->first();

        if ($subscription) {
            return response()->json(['status' => 'success', 'message' => 'Subscription verified']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid or unpaid subscription code']);
        }
    }

    public function completeSubscription(Request $request)
    {

        $user = auth()->user();
       // return $user; // افتراضيا، يمكنك استخدام الحساب المسجل حاليا
        $user->subscriptions()->update(['status' => 'completed']);
            return response()->json(['message', 'Subscription completed successfully']);
    }


}
