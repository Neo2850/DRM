<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("AdminSide/Brands/Index", [
            "brands" => Brand::paginate(9),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("AdminSide/Brands/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge([
            'slug' => str()->slug($request->input('name')),
        ]);

        //Validate
        $brandFields = $request->validate([
            'image' => ['nullable', 'file', 'max:10240'],
            'name' => ['required', 'max:255'],
            'slug' => ['required', 'max:255', 'unique:brands'],
        ]);

        //Image

        if ($request->hasFile('image')) {

            // $imagePath = $request->file('image')->store('categoryImages', 'public');
            // $categoryFields['image'] = $imagePath;

            $brandFields['image'] = Storage::disk('public')->put('brandImages', $request->image);
        }
        //Store
        Brand::create($brandFields);

        //Redirect
        return redirect()->route('brands.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {

        return Inertia::render('AdminSide/Brands/Edit', [
            "brands" => [
                "id" => $brand->id,
                "name" => $brand->name,
                "slug" => $brand->slug,
                "image" => $brand->image ? asset('storage/' . $brand->image) : null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->merge([
            'slug' => $request->filled('name')
                ? str()->slug($request->input('name'))
                : $brand->slug,
        ]);

        //Validate
        $brandFields = $request->validate([
            'image' => ['nullable', 'file', 'max:10240'],
            'name' => ['required', 'max:255'],
            'slug' => ['required', 'max:255', 'unique:brands,slug,' . $brand->id],
        ]);

        //Image

        if ($request->hasFile('image')) {

            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brandFields['image'] = Storage::disk('public')->put('brandImages', $request->image);
        } else {
            $brandFields['image'] = $brand->image;
        }
        //Store
        $brand->update($brandFields);

        //Redirect
        return redirect()->route('brands.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('brands.index');
    }
}
