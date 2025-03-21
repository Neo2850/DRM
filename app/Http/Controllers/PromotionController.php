<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotions;
use App\Models\LandingContent;
use Inertia\Inertia;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotions::all();
        return Inertia::render('AdminSide/Promotions/Index', compact('promotions'));
    }

    public function content()
    {
        $landingContents = LandingContent::all();
        return Inertia::render('AdminSide/HomeContent/Index', compact('landingContents'));
    }

    public function create()
    {
        return Inertia::render('AdminSide/Promotions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'code' => 'required|string|unique:promotions,code|max:50',
            'type' => 'required|in:item,shipping',
            'discount' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Promotions::create($validated);

        return redirect()->route('promotions.index')
            ->with('message', 'Promotion created successfully');
    }

    public function edit(Promotions $promotion)
    {
        return Inertia::render('AdminSide/Promotions/Edit', [
            'promotion' => $promotion
        ]);
    }

    public function update(Request $request, Promotions $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'type' => 'required|in:item,shipping',
            'discount' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $promotion->update($validated);

        return redirect()->route('promotions.index')
            ->with('message', 'Promotion updated successfully');
    }

    public function destroy(Promotions $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('message', 'Promotion deleted successfully');
    }
}
