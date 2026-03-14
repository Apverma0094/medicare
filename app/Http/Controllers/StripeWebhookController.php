<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
        
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            if ($endpoint_secret) {
                $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            } else {
                $event = json_decode($payload, true);
            }
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'checkout.session.completed':
                $session = $event['data']['object'];
                $this->handleSuccessfulPayment($session);
                break;
                
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                Log::info('Payment succeeded', ['payment_intent' => $paymentIntent['id']]);
                break;
                
            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                Log::info('Payment failed', ['payment_intent' => $paymentIntent['id']]);
                break;
                
            default:
                Log::info('Unhandled Stripe webhook event', ['type' => $event['type']]);
        }

        return response('Webhook handled successfully');
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment($session)
    {
        try {
            // Find order by session ID
            $order = Order::where('stripe_session_id', $session['id'])->first();
            
            if ($order) {
                // Update order status
                $order->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid'
                ]);
                
                Log::info('Order payment confirmed via webhook', [
                    'order_id' => $order->id,
                    'session_id' => $session['id']
                ]);
            } else {
                Log::warning('Order not found for Stripe session', ['session_id' => $session['id']]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error handling successful payment webhook', [
                'error' => $e->getMessage(),
                'session_id' => $session['id'] ?? 'unknown'
            ]);
        }
    }
}