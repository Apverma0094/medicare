<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserAddress;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{
    /**
     * Display user's cart
     */
    public function index(): View
    {
        $cartItems = auth()->user()->cartItems()->with('medicine')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        return view('user.cart', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart via AJAX
     */
    public function addAjax(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $user = auth()->user();

        // Check if item already exists in cart
        $cartItem = $user->cartItems()->where('medicine_id', $medicine->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check stock availability
            if ($newQuantity > $medicine->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $medicine->stock
                ]);
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Check stock availability
            if ($request->quantity > $medicine->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $medicine->stock
                ]);
            }
            
            $user->cartItems()->create([
                'medicine_id' => $medicine->id,
                'quantity' => $request->quantity,
                'price' => $medicine->price
            ]);
        }
        
        $cartCount = $user->cartItems()->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Medicine added to cart successfully!',
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Get cart count
     */
    public function getCount()
    {
        $cartCount = auth()->user()->cartItems()->count();
        
        return response()->json([
            'count' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity via AJAX
     */
    public function updateAjax(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cartItem = Cart::where('id', $request->cart_id)
                       ->where('user_id', auth()->id())
                       ->firstOrFail();

        if ($request->quantity == 0) {
            $cartItem->delete();
        } else {
            // Check stock availability
            if ($request->quantity > $cartItem->medicine->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $cartItem->medicine->stock
                ]);
            }
            
            $cartItem->update(['quantity' => $request->quantity]);
        }

        $cartItems = auth()->user()->cartItems()->with('medicine')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'total' => $total,
            'cartCount' => $cartItems->count()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($cartId): RedirectResponse
    {
        Cart::where('id', $cartId)
            ->where('user_id', auth()->id())
            ->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart');
    }

    /**
     * Clear entire cart
     */
    public function clear(): RedirectResponse
    {
        Cart::where('user_id', auth()->id())->delete();
        
        return redirect()->back()->with('success', 'Cart cleared successfully');
    }

    /**
     * Checkout process
     */
    public function checkout(): View
    {
        $cartItems = auth()->user()->cartItems()->with('medicine')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty');
        }
        
        // Get user's saved addresses
        $addresses = auth()->user()->addresses;
        
        return view('user.checkout', compact('cartItems', 'total', 'addresses'));
    }

    /**
     * Place order from cart
     */
    public function placeOrder(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // Validate based on whether using saved address or new address
        if ($request->saved_address_id && $request->saved_address_id !== 'new') {
            // Using saved address
            $request->validate([
                'saved_address_id' => 'required|exists:user_addresses,id',
                'payment_method' => 'required|in:cod,online',
                'notes' => 'nullable|string|max:500'
            ]);
            
            // Get the saved address
            $savedAddress = $user->addresses()->findOrFail($request->saved_address_id);
            $deliveryAddress = [
                'name' => $savedAddress->name,
                'phone' => $savedAddress->phone,
                'address' => $savedAddress->address,
                'city' => $savedAddress->city,
                'state' => $savedAddress->state,
                'pincode' => $savedAddress->pincode,
                'landmark' => $savedAddress->landmark,
                'label' => $savedAddress->label,
                'type' => $savedAddress->type
            ];
        } else {
            // Using new address
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
                'landmark' => 'nullable|string|max:255',
                'address_type' => 'nullable|in:home,office,other',
                'address_label' => 'nullable|string|max:50',
                'save_address' => 'nullable|boolean',
                'make_default' => 'nullable|boolean',
                'payment_method' => 'required|in:cod,online',
                'notes' => 'nullable|string|max:500'
            ]);
            
            $deliveryAddress = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'landmark' => $request->landmark,
                'label' => $request->address_label ?: ($request->address_type ?: 'Home'),
                'type' => $request->address_type ?: 'home'
            ];
            
            // Save address if requested
            if ($request->save_address) {
                // If make default is checked, unset all other defaults
                if ($request->make_default) {
                    $user->addresses()->update(['is_default' => false]);
                }
                
                $user->addresses()->create([
                    'label' => $request->address_label ?: ($request->address_type ?: 'Home'),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pincode' => $request->pincode,
                    'landmark' => $request->landmark,
                    'type' => $request->address_type ?: 'home',
                    'is_default' => $request->make_default ? true : false
                ]);
            }
        }

        $cartItems = $user->cartItems()->with('medicine')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $taxAmount = $subtotal * 0.18; // 18% GST
            $grandTotal = $subtotal + $taxAmount;

            // Check stock availability for all items
            foreach ($cartItems as $item) {
                if ($item->medicine->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->medicine->name}. Available: {$item->medicine->stock}");
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $subtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'delivery_address' => $deliveryAddress,
                'notes' => $request->notes,
                'order_date' => now()
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->quantity * $item->price
                ]);

                // Reduce medicine stock
                $item->medicine->decrement('stock', $item->quantity);
            }

            // Clear user's cart
            $user->cartItems()->delete();

            // Send notification to all admin users
            $adminUsers = User::where('role', 'admin')->get();
            Notification::send($adminUsers, new NewOrderNotification($order));

            DB::commit();

            return redirect()->route('user.order.success', $order->id)
                           ->with('success', 'Order placed successfully! Order Number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    /**
     * Order success page
     */
    public function orderSuccess($orderId): View
    {
        $order = Order::with(['orderItems.medicine', 'user'])
                     ->where('id', $orderId)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
        
        return view('user.order-success', compact('order'));
    }

    /**
     * Create Stripe payment session
     */
    public function stripePayment(Request $request)
    {
        try {
            // Log the request data for debugging
            \Log::info('Stripe Payment Request Data:', $request->all());
            
            Stripe::setApiKey(config('services.stripe.secret_key'));

            $user = auth()->user();
            $cartItems = $user->cartItems()->with('medicine')->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $taxAmount = $subtotal * 0.18; // 18% GST
            $grandTotal = $subtotal + $taxAmount;

            // Create line items for Stripe
            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => $item->medicine->name,
                            'description' => 'Quantity: ' . $item->quantity,
                        ],
                        'unit_amount' => $item->price * 100, // Convert to paise
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add tax as a line item
            if ($taxAmount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => 'GST (18%)',
                            'description' => 'Tax on medicines',
                        ],
                        'unit_amount' => $taxAmount * 100, // Convert to paise
                    ],
                    'quantity' => 1,
                ];
            }

            // Store address and other details in session for later use
            session([
                'pending_order_data' => [
                    'saved_address_id' => $request->saved_address_id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pincode' => $request->pincode,
                    'landmark' => $request->landmark,
                    'address_type' => $request->address_type,
                    'address_label' => $request->address_label,
                    'save_address' => $request->save_address,
                    'make_default' => $request->make_default,
                    'notes' => $request->notes,
                    'total_amount' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'grand_total' => $grandTotal,
                ]
            ]);

            // Create Stripe checkout session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.checkout'),
                'customer_email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'order_type' => 'medicine_order',
                ],
                'billing_address_collection' => 'auto',
                'shipping_address_collection' => [
                    'allowed_countries' => ['IN'],
                ],
            ]);

            return response()->json(['url' => $session->url]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle successful Stripe payment
     */
    public function stripeSuccess(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret_key'));

            $sessionId = $request->get('session_id');
            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('user.checkout')->with('error', 'Payment was not completed');
            }

            $user = auth()->user();
            $cartItems = $user->cartItems()->with('medicine')->get();
            $orderData = session('pending_order_data');
            
            // Log the session data for debugging
            \Log::info('Pending Order Data from Session:', $orderData ?? []);

            if (!$orderData) {
                return redirect()->route('user.checkout')->with('error', 'Order data not found');
            }

            if ($cartItems->isEmpty()) {
                return redirect()->route('user.cart')->with('error', 'Your cart is empty');
            }

            DB::beginTransaction();

            try {
                // Process address data same as in placeOrder method
                if (isset($orderData['saved_address_id']) && $orderData['saved_address_id'] && $orderData['saved_address_id'] !== 'new') {
                    $savedAddress = $user->addresses()->findOrFail($orderData['saved_address_id']);
                    $deliveryAddress = [
                        'name' => $savedAddress->name,
                        'phone' => $savedAddress->phone,
                        'address' => $savedAddress->address,
                        'city' => $savedAddress->city,
                        'state' => $savedAddress->state,
                        'pincode' => $savedAddress->pincode,
                        'landmark' => $savedAddress->landmark,
                        'label' => $savedAddress->label,
                        'type' => $savedAddress->type
                    ];
                } else {
                    // Use new address data
                    $deliveryAddress = [
                        'name' => $orderData['name'] ?? 'N/A',
                        'phone' => $orderData['phone'] ?? 'N/A',
                        'address' => $orderData['address'] ?? 'N/A',
                        'city' => $orderData['city'] ?? 'N/A',
                        'state' => $orderData['state'] ?? 'N/A',
                        'pincode' => $orderData['pincode'] ?? 'N/A',
                        'landmark' => $orderData['landmark'] ?? null,
                        'label' => $orderData['address_label'] ?? ($orderData['address_type'] ?? 'Home'),
                        'type' => $orderData['address_type'] ?? 'home'
                    ];

                    // Validate required address fields
                    if (!$orderData['name'] || !$orderData['phone'] || !$orderData['address'] || !$orderData['city'] || !$orderData['pincode']) {
                        throw new \Exception('Address information is incomplete. Please go back to checkout and fill in all required fields.');
                    }

                    // Save address if requested
                    if (isset($orderData['save_address']) && $orderData['save_address']) {
                        if (isset($orderData['make_default']) && $orderData['make_default']) {
                            $user->addresses()->update(['is_default' => false]);
                        }
                        
                        $user->addresses()->create([
                            'label' => $orderData['address_label'] ?: ($orderData['address_type'] ?: 'Home'),
                            'name' => $orderData['name'],
                            'phone' => $orderData['phone'],
                            'address' => $orderData['address'],
                            'city' => $orderData['city'],
                            'state' => $orderData['state'] ?? '',
                            'pincode' => $orderData['pincode'],
                            'landmark' => $orderData['landmark'],
                            'type' => $orderData['address_type'] ?: 'home',
                            'is_default' => isset($orderData['make_default']) ? $orderData['make_default'] : false
                        ]);
                    }
                }

                // Check stock availability
                foreach ($cartItems as $item) {
                    if ($item->medicine->stock < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$item->medicine->name}. Available: {$item->medicine->stock}");
                    }
                }

                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'total_amount' => $orderData['total_amount'],
                    'tax_amount' => $orderData['tax_amount'],
                    'grand_total' => $orderData['grand_total'],
                    'status' => 'confirmed',
                    'payment_method' => 'stripe',
                    'payment_status' => 'paid',
                    'stripe_session_id' => $sessionId,
                    'delivery_address' => $deliveryAddress,
                    'notes' => $orderData['notes'],
                    'order_date' => now()
                ]);

                // Create order items and update stock
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'medicine_id' => $item->medicine_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->quantity * $item->price
                    ]);

                    // Reduce medicine stock
                    $item->medicine->decrement('stock', $item->quantity);
                }

                // Clear user's cart
                $user->cartItems()->delete();

                // Send notification to admin users
                $adminUsers = User::where('role', 'admin')->get();
                Notification::send($adminUsers, new NewOrderNotification($order));

                // Clear session data
                session()->forget('pending_order_data');

                DB::commit();

                return redirect()->route('user.order.success', $order->id)
                               ->with('success', 'Payment successful! Order Number: ' . $order->order_number);

            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->route('user.checkout')->with('error', 'Order processing failed: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return redirect()->route('user.checkout')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancelled Stripe payment
     */
    public function stripeCancel()
    {
        // Clear any pending order data
        session()->forget('pending_order_data');
        
        return redirect()->route('user.checkout')
                        ->with('error', 'Payment was cancelled. Please try again.');
    }
}
