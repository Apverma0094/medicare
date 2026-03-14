<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminOrderController extends Controller
{
    /**
     * Display all orders for admin
     */
    public function index(): View
    {
        $orders = Order::with(['user', 'orderItems'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        
        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'pendingOrders', 'confirmedOrders', 
            'deliveredOrders', 'totalRevenue'
        ));
    }

    /**
     * Show individual order details
     */
    public function show($id): View
    {
        $order = Order::with(['user', 'orderItems.medicine'])
                     ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'delivery_date' => $newStatus === 'delivered' ? now() : $order->delivery_date
        ]);

        // If order is cancelled, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->medicine->increment('stock', $item->quantity);
            }
        }

        return back()->with('success', "Order status updated to {$newStatus} successfully!");
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', "Payment status updated to {$request->payment_status} successfully!");
    }

    /**
     * Get orders by status for dashboard
     */
    public function getOrdersByStatus($status): View
    {
        $orders = Order::with(['user', 'orderItems'])
                      ->where('status', $status)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('admin.orders.by-status', compact('orders', 'status'));
    }

    /**
     * Search orders
     */
    public function search(Request $request): View
    {
        $query = $request->get('query');
        
        $orders = Order::with(['user', 'orderItems'])
                      ->where('order_number', 'LIKE', "%{$query}%")
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('email', 'LIKE', "%{$query}%");
                      })
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('admin.orders.search', compact('orders', 'query'));
    }

    /**
     * Update refund status
     */
    public function updateRefundStatus(Request $request, $id)
    {
        $request->validate([
            'refund_status' => 'required|in:approved,processing,completed,rejected',
            'refund_notes' => 'nullable|string|max:500',
            'refund_reference' => 'nullable|string|max:100'
        ]);

        $order = Order::findOrFail($id);
        
        if ($order->refund_status === 'none') {
            return back()->with('error', 'No refund request found for this order.');
        }

        $updateData = [
            'refund_status' => $request->refund_status,
        ];

        if ($request->refund_notes) {
            $updateData['refund_notes'] = $request->refund_notes;
        }

        if ($request->refund_reference) {
            $updateData['refund_reference'] = $request->refund_reference;
        }

        if ($request->refund_status === 'completed') {
            $updateData['refund_processed_at'] = now();
        }

        $order->update($updateData);

        return back()->with('success', "Refund status updated to {$request->refund_status} successfully!");
    }

    /**
     * Delete order and restore stock
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $order = Order::with('orderItems')->findOrFail($id);
            
            // Restore medicine stock
            foreach($order->orderItems as $item) {
                $medicine = $item->medicine;
                $medicine->increment('stock', $item->quantity);
            }
            
            // Delete order (order items will be deleted due to cascade)
            $order->delete();
            
            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order deleted successfully and medicine stock restored!');
        } catch (Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
