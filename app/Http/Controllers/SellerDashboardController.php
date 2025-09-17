<?php

namespace App\Http\Controllers;

use App\Models\Machinery;
use App\Models\Order;
use App\Models\Rental;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isSeller()) {
                abort(403, 'Access denied. Seller account required.');
            }
            return $next($request);
        });
    }

    /**
     * Show seller dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get recent sales
        $recentSales = Order::where('seller_id', $user->id)
            ->with(['machinery', 'buyer'])
            ->latest()
            ->take(5)
            ->get();

        // Get active rentals
        $activeRentals = Rental::whereHas('machinery', function ($query) use ($user) {
                $query->where('seller_id', $user->id);
            })
            ->whereIn('status', ['booked', 'ongoing'])
            ->with(['machinery', 'renter'])
            ->latest()
            ->take(5)
            ->get();

        // Get statistics
        $stats = [
            'total_machinery' => Machinery::where('seller_id', $user->id)->count(),
            'active_machinery' => Machinery::where('seller_id', $user->id)->where('status', 'active')->count(),
            'total_sales' => Order::where('seller_id', $user->id)->count(),
            'total_revenue' => Order::where('seller_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('seller.dashboard', compact('recentSales', 'activeRentals', 'stats'));
    }

    /**
     * Show machinery listings
     */
    public function machinery(Request $request)
    {
        $query = Machinery::where('seller_id', Auth::id())
            ->with(['category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $machinery = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('seller.machinery', compact('machinery', 'categories'));
    }

    /**
     * Show create machinery form
     */
    public function createMachinery()
    {
        $categories = Category::where('is_active', true)->get();
        return view('seller.machinery-create', compact('categories'));
    }

    /**
     * Store new machinery
     */
    public function storeMachinery(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'weekly_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,used,refurbished',
            'availability_type' => 'required|in:sale,rent,both',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'fuel_type' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'specifications' => 'nullable|array',
        ]);

        $machinery = Machinery::create([
            'seller_id' => Auth::id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'price' => $request->price,
            'daily_rate' => $request->daily_rate,
            'weekly_rate' => $request->weekly_rate,
            'monthly_rate' => $request->monthly_rate,
            'condition' => $request->condition,
            'availability_type' => $request->availability_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'fuel_type' => $request->fuel_type,
            'location' => $request->location,
            'specifications' => $request->specifications,
            'is_available' => true,
            'status' => 'pending', // Requires admin approval
        ]);

        return redirect()->route('seller.machinery')
            ->with('success', 'Machinery listed successfully! It will be reviewed and activated soon.');
    }

    /**
     * Show edit machinery form
     */
    public function editMachinery(Machinery $machinery)
    {
        $this->authorize('update', $machinery);
        
        $categories = Category::where('is_active', true)->get();
        return view('seller.machinery-edit', compact('machinery', 'categories'));
    }

    /**
     * Update machinery
     */
    public function updateMachinery(Request $request, Machinery $machinery)
    {
        $this->authorize('update', $machinery);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'weekly_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,used,refurbished',
            'availability_type' => 'required|in:sale,rent,both',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'fuel_type' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'specifications' => 'nullable|array',
        ]);

        $machinery->update($request->except(['seller_id', 'slug']));

        return redirect()->route('seller.machinery')
            ->with('success', 'Machinery updated successfully!');
    }

    /**
     * Show sales/orders
     */
    public function sales(Request $request)
    {
        $query = Order::where('seller_id', Auth::id())
            ->with(['machinery', 'buyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $sales = $query->latest()->paginate(10);

        return view('seller.sales', compact('sales'));
    }

    /**
     * Show rentals
     */
    public function rentals(Request $request)
    {
        $query = Rental::whereHas('machinery', function ($q) {
                $q->where('seller_id', Auth::id());
            })
            ->with(['machinery', 'renter', 'order']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->latest()->paginate(10);

        return view('seller.rentals', compact('rentals'));
    }

    /**
     * Show seller profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('seller.profile', compact('user'));
    }

    /**
     * Update seller profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
        ]);

        return back()->with('success', 'Profile updated successfully');
    }
}