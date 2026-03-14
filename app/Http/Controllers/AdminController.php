<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();
        $totalUsers = User::count();
        
        // Order Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        $recentMedicines = Medicine::latest()->take(5)->get();
        $recentOrders = Order::with(['user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMedicines', 'totalUsers', 'totalOrders', 'pendingOrders', 
            'confirmedOrders', 'deliveredOrders', 'totalRevenue', 'todayOrders',
            'recentMedicines', 'recentOrders'
        ));
    }

    /**
     * Show recent orders page
     */
    public function recentOrders()
    {
        try {
            // Show orders from last 30 days, or limit to last 50 orders if more recent
            $thirtyDaysAgo = now()->subDays(30);
            
            $recentOrders = Order::with(['user', 'orderItems.medicine'])
                                ->where('created_at', '>=', $thirtyDaysAgo)
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);
            
            // If no orders in last 30 days, show last 50 orders
            if ($recentOrders->total() == 0) {
                $recentOrders = Order::with(['user', 'orderItems.medicine'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(50)
                                    ->paginate(15);
            }
            
            $todayOrders = Order::whereDate('created_at', today())->count();
            $thisWeekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $thisMonthOrders = Order::whereMonth('created_at', now()->month)->count();
            
            return view('admin.recent-orders', compact('recentOrders', 'todayOrders', 'thisWeekOrders', 'thisMonthOrders'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading recent orders: ' . $e->getMessage());
        }
    }

    /**
     * Show recent medicines page
     */
    public function recentMedicines()
    {
        try {
            // Show medicines from last 30 days, or limit to last 50 medicines if more recent
            $thirtyDaysAgo = now()->subDays(30);
            
            $recentMedicines = Medicine::where('created_at', '>=', $thirtyDaysAgo)
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(15);
            
            // If no medicines in last 30 days, show last 50 medicines
            if ($recentMedicines->total() == 0) {
                $recentMedicines = Medicine::orderBy('created_at', 'desc')
                                          ->limit(50)
                                          ->paginate(15);
            }
            
            $todayMedicines = Medicine::whereDate('created_at', today())->count();
            $thisWeekMedicines = Medicine::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $lowStockMedicines = Medicine::where('stock', '<=', 10)->count();
            $outOfStockMedicines = Medicine::where('stock', 0)->count();
            
            return view('admin.recent-medicines', compact(
                'recentMedicines', 'todayMedicines', 'thisWeekMedicines', 
                'lowStockMedicines', 'outOfStockMedicines'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading recent medicines: ' . $e->getMessage());
        }
    }
}
