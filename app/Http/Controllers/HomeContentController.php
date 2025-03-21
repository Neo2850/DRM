<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeContentController extends Controller
{
    public function index()
    {
        $landingContents = LandingContent::all();
        return Inertia::render('AdminSide/HomeContent/Index', compact('landingContents'));
    }

    public function create()
    {
        return Inertia::render('AdminSide/HomeContent/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hero' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('landing', 'public');
        }

        if ($validated['is_active']) {
            // Deactivate all other content if this one is active
            LandingContent::where('is_active', true)->update(['is_active' => false]);
        }

        LandingContent::create($validated);

        return redirect()->route('home-content.index')
            ->with('message', 'Content created successfully');
    }

    public function edit(LandingContent $homeContent)
    {
        return Inertia::render('AdminSide/HomeContent/Edit', [
            'content' => $homeContent
        ]);
    }

    public function update(Request $request, LandingContent $homeContent)
    {
        $validated = $request->validate([
            'hero' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('landing', 'public');
        }

        if ($validated['is_active']) {
            // Deactivate all other content if this one is active
            LandingContent::where('id', '!=', $homeContent->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $homeContent->update($validated);

        return redirect()->route('home-content.index')
            ->with('message', 'Content updated successfully');
    }

    public function destroy(LandingContent $homeContent)
    {
        $homeContent->delete();

        return redirect()->route('home-content.index')
            ->with('message', 'Content deleted successfully');
    }
}
