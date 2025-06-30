<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $orderId = $request['order_id'];
        $transactionStatus = $request['transaction_status'];
        $fraudStatus = $request['fraud_status'];
        $grossAmount = $request['gross_amount'];

        $order = Order::findOrFail($orderId);
        $orderStatus = false;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $orderStatus = true;
            }
        } else if ($transactionStatus == 'settlement') {
            // TODO set transaction status on your database to 'success'
            // and response with 200 OK
            $orderStatus = true;
        } else if (
            $transactionStatus == 'cancel' ||
            $transactionStatus == 'deny' ||
            $transactionStatus == 'expire'
        ) {
            // TODO set transaction status on your database to 'failure'
            // and response with 200 OK
        } else if ($transactionStatus == 'pending') {
            // TODO set transaction status on your database to 'pending' / waiting payment
            // and response with 200 OK
        }

        if ($orderStatus) {
            $order->update([
                'paid_amount' => $grossAmount,
                'done_at' => now()
            ]);

            $order->save();
        }
    }
}
