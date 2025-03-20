<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("AdminSide/Categories/Index", [
            'categories' => Category::paginate(9)
        ]);
    }


    public function create()
    {
        return Inertia::render("AdminSide/Categories/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => str()->slug($request->input('name')),
            'sku' => strtoupper( // Generate SKU
                preg_replace('/[aeiou\s]/i', '', $request->input('name'))
            )
        ]);

        // Limit SKU to first 3 characters
        $request->merge([
            'sku' => substr($request->input('sku'), 0, 3),
        ]);

        //Validate
        $categoryFields = $request->validate([
            'image' => ['nullable', 'file', 'max:10240'],
            'name' => ['required', 'max:255'],
            'slug' => ['required', 'max:255', 'unique:categories'],
            'sku' => ['required', 'max:3', 'unique:categories'],
            'specifications' => ['nullable', 'array'],
            'specifications.*' => ['nullable', 'string', 'max:255'],
        ]);

        //Image

        if ($request->hasFile('image')) {

            // $imagePath = $request->file('image')->store('categoryImages', 'public');
            // $categoryFields['image'] = $imagePath;

            $categoryFields['image'] = Storage::disk('public')->put('categoryImages', $request->image);
        }
        //Store
        $category = Category::create($categoryFields);

        if (!empty($categoryFields['specifications'])) {
            foreach ($categoryFields['specifications'] as $specification) {
                if (!empty($specification)) {
                    $category->specifications()->create(['name' => $specification]);
                }
            }
        }

        //Redirect
        return redirect()->route('categories.index')
            ->with('message', 'Category created successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render("AdminSide/Categories/Edit", [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'sku' => $category->sku,
                'image' => $category->image ? asset('storage/' . $category->image) : null, // Add full image URL or null
                'specifications' => $category->specifications->map(function ($specification) {
                return [
                    'id' => $specification->id,
                    'name' => $specification->name,
                ];
            }),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->merge([
            'slug' => $request->filled('name')
                ? str()->slug($request->input('name'))
                : $category->slug,
            'sku' => $request->filled('name')
                ? strtoupper(preg_replace('/[aeiou\s]/i', '', $request->input('name')))
                : $category->sku,
        ]);

        // Limit SKU to first 3 characters
        $request->merge([
            'sku' => substr($request->input('sku'), 0, 3),
        ]);

        // Validate the request
        $categoryFields = $request->validate([
            'image' => ['nullable', 'file', 'max:10240'],
            'name' => ['required', 'max:255'],
            'slug' => ['required', 'max:255', 'unique:categories,slug,' . $category->id],
            'sku' => ['required', 'max:3', 'unique:categories,sku,' . $category->id],
            'specifications' => ['nullable', 'array'], // Specifications as array
            'specifications.*.id' => ['nullable', 'integer'], // Validate IDs for existing specifications
            'specifications.*.name' => ['nullable', 'string', 'max:255'], // Validate names for all specifications
        ]);

    //Image

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $categoryFields['image'] = Storage::disk('public')->put('categoryImages', $request->image);
        } else {
            $categoryFields['image'] = $category->image;
        }

        $category->update($categoryFields);



    // Update specifications
        $existingIds = $category->specifications()->pluck('id')->toArray(); // Get current specification IDs
        $updatedIds = []; // To track IDs that were updated or added

        foreach ($categoryFields['specifications'] ?? [] as $spec) {
            if (isset($spec['id']) && in_array($spec['id'], $existingIds)) {
                // Update existing specification
                $category->specifications()->where('id', $spec['id'])->update(['name' => $spec['name']]);
                $updatedIds[] = $spec['id']; // Add to updated IDs
            } elseif (!empty($spec['name'])) {
                // Add new specification
                $newSpec = $category->specifications()->create(['name' => $spec['name']]);
                $updatedIds[] = $newSpec->id; // Add new ID to updated IDs
            }
        }

        // Delete removed specifications
        $category->specifications()->whereNotIn('id', $updatedIds)->delete();

        return redirect()->route('categories.index')
            ->with('message', 'Category updated successfully')
            ->with('type', 'success');
    }




    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        try {
            $category->delete();

            return redirect()->route('categories.index')
                ->with('message', 'Category deleted successfully')
                ->with('type', 'success');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting category: ' . $e->getMessage()]);
        }
    }
}
