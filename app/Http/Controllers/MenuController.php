<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean'
        ]);

        $defaultCategory = Category::firstOrCreate(
            ['name' => 'General'],
            ['slug' => 'general']
        );
        $validated['category_id'] = $defaultCategory->id;

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        Menu::create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean'
        ]);

        $defaultCategory = Category::firstOrCreate(
            ['name' => 'General'],
            ['slug' => 'general']
        );
        $validated['category_id'] = $defaultCategory->id;

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($menu->image && str_starts_with($menu->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $menu->image));
            }
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $menu->update($validated);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image && str_starts_with($menu->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $menu->image));
        }
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
