<?php

namespace App\Http\Controllers;

use App\Models\Machinery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MachineryController extends Controller
{
    /**
     * Display the specified machinery
     */
    public function show(Machinery $machinery)
    {
        // Increment view count
        $machinery->incrementViewCount();

        // Load relationships
        $machinery->load(['category', 'seller', 'reviews.user']);

        // Get related machinery
        $relatedMachinery = Machinery::active()
            ->available()
            ->where('category_id', $machinery->category_id)
            ->where('id', '!=', $machinery->id)
            ->take(4)
            ->get();

        // Calculate average rating
        $averageRating = $machinery->averageRating();
        $reviewCount = $machinery->reviewCount();

        return view('machinery.show', compact(
            'machinery', 
            'relatedMachinery', 
            'averageRating', 
            'reviewCount'
        ));
    }

    /**
     * Show machinery by category
     */
    public function category(Category $category)
    {
        $machinery = Machinery::active()
            ->available()
            ->where('category_id', $category->id)
            ->with(['seller'])
            ->paginate(12);

        return view('machinery.category', compact('category', 'machinery'));
    }

    /**
     * Search machinery
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('machinery.browse');
        }

        $machinery = Machinery::active()
            ->available()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('brand', 'like', '%' . $query . '%')
                  ->orWhere('model', 'like', '%' . $query . '%');
            })
            ->with(['category', 'seller'])
            ->paginate(12);

        return view('machinery.search', compact('machinery', 'query'));
    }

    /**
     * Compare machinery
     */
    public function compare(Request $request)
    {
        $machineryIds = $request->get('ids', []);
        
        if (empty($machineryIds) || count($machineryIds) > 3) {
            return redirect()->back()->with('error', 'Please select 2-3 machinery to compare');
        }

        $machinery = Machinery::active()
            ->whereIn('id', $machineryIds)
            ->with(['category', 'seller'])
            ->get();

        if ($machinery->count() != count($machineryIds)) {
            return redirect()->back()->with('error', 'Some machinery items are no longer available');
        }

        return view('machinery.compare', compact('machinery'));
    }

    /**
     * Check availability for rental dates
     */
    public function checkAvailability(Request $request, Machinery $machinery)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        if (!$machinery->isAvailableForRent()) {
            return response()->json([
                'available' => false,
                'message' => 'This machinery is not available for rent'
            ]);
        }

        // Check for conflicting rentals
        $conflictingRentals = $machinery->rentals()
            ->whereIn('status', ['booked', 'ongoing'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($conflictingRentals) {
            return response()->json([
                'available' => false,
                'message' => 'This machinery is already booked for the selected dates'
            ]);
        }

        // Calculate rental cost
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalCost = $days * $machinery->daily_rate;

        return response()->json([
            'available' => true,
            'days' => $days,
            'daily_rate' => $machinery->daily_rate,
            'total_cost' => $totalCost,
            'message' => "Available for {$days} days"
        ]);
    }
}