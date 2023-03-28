<?php

namespace App\Http\Controllers;


use Stripe\PaymentIntent;
use Stripe\Stripe;
use Error;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generateClient(Request $request)
    {
        $total = $request->get("total");
        $total = $total < 21 ? $total + 1.99 : $total + 4.99;
        try {
            Stripe::setApiKey(env(key: "SK_STRIPE"));
            $paymentIntent = PaymentIntent::create([
                "amount" =>  $total * 100,
                'currency' => 'eur',
            ]);
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
            return response()->json($output, 200);
        } catch (Error $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
