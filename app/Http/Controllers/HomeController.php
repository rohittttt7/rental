<?php

namespace App\Http\Controllers;

use App\Models\Machinery;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Get featured machinery (latest 8)
        $featuredMachinery = Machinery::active()
            ->available()
            ->with(['category', 'seller'])
            ->latest()
            ->take(8)
            ->get();

        // Get active categories
        $categories = Category::where('is_active', true)
            ->withCount('activeMachinery')
            ->take(6)
            ->get();

        // Get statistics
        $stats = [
            'total_machinery' => Machinery::active()->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_sellers' => \App\Models\User::where('role', 'seller')->count(),
            'total_rentals' => \App\Models\Rental::count(),
        ];

        return view('home', compact('featuredMachinery', 'categories', 'stats'));
    }

    /**
     * Display machinery search/browse page
     */
    public function browse(Request $request)
    {
        $query = Machinery::active()->available()->with(['category', 'seller']);

        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'sale') {
                $query->forSale();
            } elseif ($request->type === 'rent') {
                $query->forRent();
            }
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $machinery = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('machinery.browse', compact('machinery', 'categories'));
    }
}