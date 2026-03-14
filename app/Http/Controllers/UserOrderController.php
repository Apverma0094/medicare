<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\View\View;

class UserOrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index(): View
    {
        $orders = Order::with(['orderItems.medicine'])
                      ->where('user_id', auth()->id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function show($id): View
    {
        $order = Order::with(['orderItems.medicine', 'user'])
                     ->where('id', $id)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        return view('user.orders.show', compact('order'));
    }

    /**
     * Cancel order with reason
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $order = Order::with('orderItems')
                     ->where('id', $id)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Cannot cancel order. Order is already being processed or delivered.');
        }
        
        // Restore medicine stock
        foreach($order->orderItems as $item) {
            $medicine = $item->medicine;
            $medicine->increment('stock', $item->quantity);
        }
        
        // Update order status with cancellation details
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now()
        ]);
        
        // If payment was made, initiate refund process
        if ($order->payment_status === 'paid' && $order->payment_method !== 'cod') {
            $order->update([
                'refund_status' => 'requested',
                'refund_amount' => $order->grand_total,
                'refund_requested_at' => now()
            ]);
        }
        
        return back()->with('success', 'Order cancelled successfully! Refund will be processed if applicable.');
    }

    /**
     * Request refund for delivered order
     */
    public function requestRefund(Request $request, $id)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:500',
            'refund_amount' => 'required|numeric|min:1'
        ]);

        $order = Order::where('id', $id)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        if ($order->status !== 'delivered') {
            return back()->with('error', 'Refund can only be requested for delivered orders.');
        }
        
        if ($order->refund_status !== 'none') {
            return back()->with('error', 'Refund request already exists for this order.');
        }

        // Check if refund is within allowed timeframe (7 days)
        $deliveryDate = $order->delivery_date ?? $order->updated_at;
        if ($deliveryDate->diffInDays(now()) > 7) {
            return back()->with('error', 'Refund can only be requested within 7 days of delivery.');
        }
        
        $refundAmount = min($request->refund_amount, $order->grand_total);
        
        $order->update([
            'refund_status' => 'requested',
            'refund_amount' => $refundAmount,
            'refund_requested_at' => now(),
            'refund_notes' => $request->refund_reason
        ]);
        
        return back()->with('success', 'Refund request submitted successfully! It will be reviewed by our team.');
    }

    /**
     * Return/Exchange request
     */
    public function requestReturn(Request $request, $id)
    {
        $request->validate([
            'return_reason' => 'required|string|max:500',
            'return_type' => 'required|in:return,exchange'
        ]);

        $order = Order::where('id', $id)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        if ($order->status !== 'delivered') {
            return back()->with('error', 'Return/Exchange can only be requested for delivered orders.');
        }

        // Check if return is within allowed timeframe (7 days)
        $deliveryDate = $order->delivery_date ?? $order->updated_at;
        if ($deliveryDate->diffInDays(now()) > 7) {
            return back()->with('error', 'Return/Exchange can only be requested within 7 days of delivery.');
        }
        
        // Create return request (you can create a separate returns table later)
        $order->update([
            'refund_status' => $request->return_type === 'return' ? 'requested' : 'none',
            'refund_notes' => "Return/Exchange requested: " . $request->return_reason,
            'refund_requested_at' => now()
        ]);
        
        return back()->with('success', ucfirst($request->return_type) . ' request submitted successfully!');
    }

    /**
     * Track order
     */
    public function track($id): View
    {
        $order = Order::with(['orderItems.medicine'])
                     ->where('id', $id)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        $statusSteps = [
            'pending' => ['icon' => '⏳', 'title' => 'Order Placed', 'desc' => 'Your order has been received'],
            'confirmed' => ['icon' => '✅', 'title' => 'Order Confirmed', 'desc' => 'Order has been confirmed by admin'],
            'processing' => ['icon' => '🔄', 'title' => 'Processing', 'desc' => 'Order is being prepared'],
            'shipped' => ['icon' => '🚚', 'title' => 'Shipped', 'desc' => 'Order is on the way'],
            'delivered' => ['icon' => '📦', 'title' => 'Delivered', 'desc' => 'Order has been delivered'],
            'cancelled' => ['icon' => '❌', 'title' => 'Cancelled', 'desc' => 'Order has been cancelled']
        ];
        
        return view('user.orders.track', compact('order', 'statusSteps'));
    }

    /**
     * Get user's order count for header badge (excluding delivered orders)
     */
    public function getCount()
    {
        $count = Order::where('user_id', auth()->id())
                     ->where('status', '!=', 'delivered')
                     ->count();
        return response()->json(['count' => $count]);
    }
}
