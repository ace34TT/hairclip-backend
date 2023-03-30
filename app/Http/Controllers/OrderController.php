<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\StockMovement;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function generateClient(Request $request)
    {
        // $total = $request->get("total");
        // try {
        //     Stripe::setApiKey(env(key: "SK_STRIPE"));
        //     $paymentIntent = PaymentIntent::create([
        //         "amount" =>  $total * 100,
        //         'currency' => 'eur',
        //     ]);
        //     $output = [
        //         'clientSecret' => $paymentIntent->client_secret,
        //     ];
        //     return response()->json($output, 200);
        // } catch (Error $e) {
        //     return response()->json(["error" => $e->getMessage()], 500);
        // }
        return response()->json(["message" => "ok"], 200);
    }

    public function paymentSuccess(Request $request)
    {
        $data = $request->all();
        $key = substr(explode("secret", $data["client_secret"], -1)[0], 0, -1);
        Stripe::setApiKey(env(key: "SK_STRIPE"));
        $paymentIntent = PaymentIntent::retrieve($key,  ['expand' => ['payment_method']]);
        $currentDate = now();
        $order = [
            'payment_intent_id' => $paymentIntent->id,
            //
            'customer_first_name' => $data["shippingInformation"]["firstname"],
            'customer_last_name' => $data["shippingInformation"]["lastname"],
            'customer_emil' =>  $data["shippingInformation"]["email"],
            "customer_phone" => $data["shippingInformation"]["phone"],
            //
            'shipping_address' => $data["shippingInformation"]["shipping_address"],
            "shipping_city" => $data["shippingInformation"]["town"],
            "shipping_state" => $data["shippingInformation"]["province"],
            "shipping_postal_code" => $data["shippingInformation"]["zip_code"],
            //
            'billing_address' =>  $data["shippingInformation"]["shipping_address"],
            //
            "quantity" => $data["cart"]["quantity"],
            "amount" => $data["cart"]["subTotal"],
            "shipping" => $data["cart"]["shipping"],
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ];
        $orderDoc = Order::create($order);
        $orderDetails = [];
        $stockMovements = [];
        foreach ($data['cart']["products"] as $key => $cartDetail) {
            $orderDetails[] = [
                "order_id" => $orderDoc->id,
                "product_id" => $cartDetail["id"],
                "quantity" => $cartDetail["quantity"],
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];
            $stockMovements[] = [
                "product_id" => $cartDetail["id"],
                "quantity" => $cartDetail["quantity"],
                "type" => 1,
                "created_at" => $currentDate,
                "updated_at" => $currentDate,
            ];
        }
        OrderDetail::insert($orderDetails);
        StockMovement::insert($stockMovements);
        Mail::to($data["shippingInformation"]["email"])->send(new ConfirmOrder($data["cart"]["subTotal"],  $data["shippingInformation"]["lastname"] . " " . $data["shippingInformation"]["firstname"], $currentDate, $orderDoc->id, $data["cart"]["shipping"]));
        return response()->json(["message" => "data added successfully", "order_id" => $orderDoc->id, "date" => $currentDate], 200);
    }
}
