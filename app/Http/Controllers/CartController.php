<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Show the shopping cart
     */
    public function index()
    {
        $cartItems = CartItem::with('machinery.category')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, Machinery $machinery)
    {
        $request->validate([
            'type' => 'required|in:purchase,rental',
            'quantity' => 'required_if:type,purchase|integer|min:1',
            'rental_start_date' => 'required_if:type,rental|date|after_or_equal:today',
            'rental_end_date' => 'required_if:type,rental|date|after:rental_start_date',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to cart'
            ], 401);
        }

        // Check if machinery is available for the requested type
        if ($request->type === 'purchase' && !$machinery->isAvailableForSale()) {
            return response()->json([
                'success' => false,
                'message' => 'This machinery is not available for purchase'
            ]);
        }

        if ($request->type === 'rental' && !$machinery->isAvailableForRent()) {
            return response()->json([
                'success' => false,
                'message' => 'This machinery is not available for rental'
            ]);
        }

        // Check if item already exists in cart
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('machinery_id', $machinery->id)
            ->where('type', $request->type)
            ->first();

        if ($existingItem) {
            if ($request->type === 'purchase') {
                $existingItem->quantity += $request->quantity ?? 1;
                $existingItem->updateTotalPrice();
            } else {
                // For rental, update dates
                $existingItem->rental_start_date = $request->rental_start_date;
                $existingItem->rental_end_date = $request->rental_end_date;
                $existingItem->updateTotalPrice();
            }
        } else {
            $cartItem = new CartItem([
                'user_id' => Auth::id(),
                'machinery_id' => $machinery->id,
                'type' => $request->type,
                'quantity' => $request->type === 'purchase' ? ($request->quantity ?? 1) : 1,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'unit_price' => $request->type === 'purchase' ? $machinery->price : $machinery->daily_rate,
            ]);

            $cartItem->updateTotalPrice();
        }

        $cartCount = CartItem::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update cart item
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => 'required_if:type,purchase|integer|min:1',
            'rental_start_date' => 'required_if:type,rental|date|after_or_equal:today',
            'rental_end_date' => 'required_if:type,rental|date|after:rental_start_date',
        ]);

        if ($cartItem->type === 'purchase') {
            $cartItem->quantity = $request->quantity;
        } else {
            $cartItem->rental_start_date = $request->rental_start_date;
            $cartItem->rental_end_date = $request->rental_end_date;
        }

        $cartItem->updateTotalPrice();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'total_price' => $cartItem->total_price
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        $cartCount = CartItem::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get cart count (for AJAX)
     */
    public function count()
    {
        $count = Auth::check() ? CartItem::where('user_id', Auth::id())->count() : 0;

        return response()->json(['count' => $count]);
    }
}