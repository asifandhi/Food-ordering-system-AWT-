<?php

namespace App\Http\Controllers\Hotelier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $hotelierId = Auth::user()->hotelierProfile->id;
        $categories = Category::where('hotelier_id', $hotelierId)
                        ->with('foodItems')
                        ->get();

        return view('hotelier.menu', compact('categories'));
    }

    // ── Categories ─────────────────────────────────────────
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Category::create([
            'hotelier_id' => Auth::user()->hotelierProfile->id,
            'name'        => $request->name,
            'status'      => 1,
        ]);

        return back()->with('success', 'Category added!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $category = Category::where('id', $id)
                    ->where('hotelier_id', Auth::user()->hotelierProfile->id)
                    ->firstOrFail();

        $category->update(['name' => $request->name]);

        return back()->with('success', 'Category updated!');
    }

    public function deleteCategory($id)
    {
        $category = Category::where('id', $id)
                    ->where('hotelier_id', Auth::user()->hotelierProfile->id)
                    ->firstOrFail();

        $category->delete();

        return back()->with('success', 'Category deleted!');
    }

    // ── Food Items ─────────────────────────────────────────
    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:150',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_veg'      => 'required|in:0,1',
            'image'       => 'nullable|image|max:2048',
        ]);

        $hotelierId = Auth::user()->hotelierProfile->id;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $img      = $request->file('image');
            $imgName  = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/food'), $imgName);
            $imagePath = 'uploads/food/' . $imgName;
        }

        FoodItem::create([
            'hotelier_id' => $hotelierId,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $imagePath,
            'is_veg'      => $request->is_veg,
            'is_available'=> 1,
        ]);

        return back()->with('success', 'Food item added!');
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_veg'      => 'required|in:0,1',
        ]);

        $item = FoodItem::where('id', $id)
                ->where('hotelier_id', Auth::user()->hotelierProfile->id)
                ->firstOrFail();

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            $img      = $request->file('image');
            $imgName  = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/food'), $imgName);
            $imagePath = 'uploads/food/' . $imgName;
        }

        $item->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $imagePath,
            'is_veg'      => $request->is_veg,
        ]);

        return back()->with('success', 'Food item updated!');
    }

    public function deleteItem($id)
    {
        $item = FoodItem::where('id', $id)
                ->where('hotelier_id', Auth::user()->hotelierProfile->id)
                ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Food item deleted!');
    }

    public function toggleItem($id)
    {
        $item = FoodItem::where('id', $id)
                ->where('hotelier_id', Auth::user()->hotelierProfile->id)
                ->firstOrFail();

        $item->update(['is_available' => !$item->is_available]);

        return back()->with('success', $item->is_available
            ? 'Item is now AVAILABLE.'
            : 'Item is now UNAVAILABLE.');
    }
}