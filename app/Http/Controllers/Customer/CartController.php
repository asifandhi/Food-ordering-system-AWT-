<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()
                        ->with('foodItem.hotelier')
                        ->get();

        $subtotal = $cartItems->sum(fn($c) => $c->quantity * $c->foodItem->price);

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:food_items,id',
        ]);

        $userId = Auth::id();
        $item   = FoodItem::findOrFail($request->item_id);

        // Check if cart has items from a different restaurant
        $existingCart = Cart::where('user_id', $userId)->with('foodItem')->first();
        if ($existingCart && $existingCart->foodItem->hotelier_id !== $item->hotelier_id) {
            return back()->with('error',
                'Your cart has items from another restaurant. Clear cart first.');
        }

        // If item already in cart — increase quantity
        $cartItem = Cart::where('user_id', $userId)
                        ->where('item_id', $request->item_id)
                        ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id'  => $userId,
                'item_id'  => $request->item_id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', $item->name . ' added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:20']);

        $cartItem = Cart::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared.');
    }
}