<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SubscriptionCodeMail;
use App\Models\Payment;
use App\Models\SubscriptionInCourse;
use App\Models\User;
use App\Notifications\SubscriptionCodeNotification;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stripe\Charge;
use Stripe\Stripe;


class PaymentController extends Controller
{
    protected $payPalService;

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $Payment=Payment::all();
    //     return $Payment;
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'code'                  =>       'integer',
    //             'name'                  =>       'required|string|max:255',
    //             'type'                  =>       'string|required',
    //             'email'                 =>       'required',
    //             'payment_details'       =>       'string',
    //             'expired_date'          =>       'required|date',
    //             'card_validation'       =>       'nullable',
    //             'subscription'          =>       'in:0,1',

    //         ]);

    //         $Payment = Payment::Create([
    //             'code' => $request->code,
    //             'name' => $request->name,
    //             'type' => $request->type,
    //             'email' => $request->email,
    //             'payment_details' => $request->payment_details,
    //             'expired_date' => $request->expired_date,
    //             'card_validation' => $request->card_validation,
    //             'subscription' => $request->subscription,
    //         ]);



    //         return response()->json([
    //             'message' => 'Payment Created Successfully',
    //             'data' =>  $Payment,
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'message' => 'Validation Error',
    //             'errors' => $e->errors(),
    //         ], 400);
    //     }
    // }


    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }


    public function processSubscription(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'name_on_card' => 'required|string',
        'card_number' => 'required|string',
        'cvc' => 'required|string',
        'payment_type' => 'required|string',
        'expiry_date' => 'required|date',
    ]);
    try {
        $paymentLink = $this->payPalService->createPayment(50); // استخدم المبلغ المناسب هنا
        return  ($paymentLink);
    } catch (\Exception $ex) {
        return response()->json(['message'=>'Error In Payment']);
    }
}


public function success(Request $request)
    {

        if (empty($request->input('paymentId')) || empty($request->input('PayerID'))) {
            return response()->json(['error', 'فشلت عملية الدفع']);
        }

        try {
            $result = $this->payPalService->executePayment($request->input('paymentId'), $request->input('PayerID'));

            $subscriptionCode = strtoupper(str_random(10));

            Mail::raw("Your subscription code is: $subscriptionCode", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Subscription Code');
            });

        return response()->json([
            'message' => 'You have successfully subscribed',
            'warning' => 'لقد اتممت عملية الاشتراك بنجاح وتم ارسال الكود الى البريد الالكتروني ولا يسمح بستخدام الكود الا على جهاز واحد فقط وعند الاستخدام على اكثر من جهاز سيتم الغاء الاشتراك'
        ]);

        } catch (\Exception $ex) {
            return response()->json([['error', 'حدث خطأ أثناء معالجة الدفع باستخدام PayPal']]);
        }
    }

    public function cancel()
    {
        return response()->json([['error', 'cancel paypal']]);
    }

// public function processPayment(Request $request)
// {
//     // تهيئة مفاتيح الوصول لـ Stripe
//     Stripe::setApiKey(config('app.STRIPE_SECRET'));

//     // استلام معلومات الدفع من الطلب
//     $amount = $request->input('amount');
//     $token = $request->input('token');

//     // تنفيذ عملية الدفع باستخدام بوابة Stripe
//     $charge = \Stripe\Charge::create([
//         'amount' => $amount,
//         'currency' => 'USD',
//         'source' => $token,
//     ]);

//     // قم بتنفيذ أي إجراءات إضافية مثل تحديث قاعدة البيانات أو إرسال رسالة تأكيد

//     // عودة الاستجابة
//     return response()->json(['message' => 'تمت عملية الدفع بنجاح']);
// }
// }
// STRIPE_KEY=your_stripe_key
// STRIPE_SECRET=your_stripe_secret
    /**
     * Display the specified resource.
     */
    // public function show(Payment $payment)
    // {
    //     $payment = Payment::find($payment->id);

    //     return response()->json($payment);

    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            $request->validate([
                'c' => 'required|email',
        'name_on_card' => 'required|string',
        'card_number' => 'required|string',
        'cvc' => 'required|string',
        'payment_type' => 'required|string',
        'expiry_date' => 'required|date',


            ]);

            $Payment = Payment::where('id',$payment->id)->update([
                'name_on_card' => $request->name_on_card,
                'payment_type' => $request->payment_type,
                'email' => $request->email,
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'cvc' => $request->cvc,
            ]);



            return response()->json([
                'message' => 'Payment Updated Successfully',
                'data' =>  $Payment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }


    // public function PaymentBy_Credit()
    // {
    //     $payByCredit=Payment::first();
    //    // return   $payByCredit;
    //    if($payByCredit->subscription==1){
    //     return response()->json([
    //         'message' => 'تم الاشتراك بنجاح',
    //         'code' => 'd5hy8d',
    //         'warning' => 'لقد اتممت عملية الاشتراك بنجاح وتم ارسال الكود الى البريد الالكتروني ولا يسمح بستخدام الكود الا على جهاز واحد فقط وعند الاستخدام على اكثر من جهاز سيتم الغاء الاشتراك'
    //     ]);
    // }else{
    //     return false;
    // }
    // }
    // public function PaymentBy_Paypal()
    // {
    //     $payByCredit=Payment::where('type','card')->get();
    //     return   $payByCredit;
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json([
            'message' => 'Payment Deleted Successfully',
        ], 201);
    }
}
