<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rental;
use App\Models\CartItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show customer dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get recent orders
        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['machinery', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        // Get active rentals
        $activeRentals = Rental::where('renter_id', $user->id)
            ->whereIn('status', ['booked', 'ongoing'])
            ->with(['machinery', 'order'])
            ->latest()
            ->take(5)
            ->get();

        // Get statistics
        $stats = [
            'total_orders' => Order::where('buyer_id', $user->id)->count(),
            'total_rentals' => Rental::where('renter_id', $user->id)->count(),
            'active_rentals' => Rental::where('renter_id', $user->id)
                ->whereIn('status', ['booked', 'ongoing'])
                ->count(),
            'cart_items' => CartItem::where('user_id', $user->id)->count(),
        ];

        return view('customer.dashboard', compact('recentOrders', 'activeRentals', 'stats'));
    }

    /**
     * Show all orders
     */
    public function orders(Request $request)
    {
        $query = Order::where('buyer_id', Auth::id())
            ->with(['machinery', 'seller']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $orders = $query->latest()->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Show all rentals
     */
    public function rentals(Request $request)
    {
        $query = Rental::where('renter_id', Auth::id())
            ->with(['machinery', 'order']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->latest()->paginate(10);

        return view('customer.rentals', compact('rentals'));
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Show reviews written by customer
     */
    public function reviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['machinery', 'order'])
            ->latest()
            ->paginate(10);

        return view('customer.reviews', compact('reviews'));
    }
}