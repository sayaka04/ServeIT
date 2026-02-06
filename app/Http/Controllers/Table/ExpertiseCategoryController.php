<?php

namespace App\Http\Controllers\Table;

use App\Models\ExpertiseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpertiseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * Fetches non-archived categories by default, or archived categories if requested.
     */
    public function index(Request $request)
    {
        $query = ExpertiseCategory::orderBy('name');

        // Check for an 'archived' query parameter to filter results
        if ($request->has('archived') && $request->get('archived') === 'true') {
            // Show only archived categories
            $categories = $query->where('is_archived', true)->get();
        } else {
            // Show only active (non-archived) categories by default
            $categories = $query->where('is_archived', false)->get();
        }

        return view('expertise_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expertise_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:expertise_categories,name',
            'description' => 'nullable|string|max:255',
            // is_archived will default to false at creation in the model/migration
        ]);

        ExpertiseCategory::create($validated);

        return redirect()->route('expertise-categories.index')
            ->with('success', 'Expertise category created successfully.');
    }

    /**
     * Display the specified resource.
     * This may not be heavily used, but is included for completeness.
     */
    public function show(ExpertiseCategory $expertiseCategory)
    {
        return view('expertise_categories.show', compact('expertiseCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpertiseCategory $expertiseCategory)
    {
        return view('expertise_categories.edit', compact('expertiseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpertiseCategory $expertiseCategory)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                // Ensure the name is unique, but ignore the current category's ID
                Rule::unique('expertise_categories', 'name')->ignore($expertiseCategory->id),
            ],
            'description' => 'nullable|string|max:255',
            // 'is_archived' is typically updated via the dedicated archive/restore methods below
        ]);

        $expertiseCategory->update($validated);

        return redirect()->route('expertise-categories.index')
            ->with('success', 'Expertise category updated successfully.');
    }

    /**
     * Archive the specified resource by setting is_archived to true.
     * This acts as a soft-delete mechanism for deactivating a category.
     */
    public function archive(ExpertiseCategory $expertiseCategory)
    {
        $expertiseCategory->update(['is_archived' => true]);

        return redirect()->route('expertise-categories.index')
            ->with('success', 'Expertise category archived successfully.');
    }

    /**
     * Restore the specified archived resource by setting is_archived to false.
     * This reactivates a category.
     */
    public function restore(ExpertiseCategory $expertiseCategory)
    {
        $expertiseCategory->update(['is_archived' => false]);

        return redirect()->route('expertise-categories.index')
            ->with('success', 'Expertise category restored successfully.');
    }

    /**
     * Remove the specified resource from storage (Permanent Hard Delete).
     * It is recommended to use the `archive` method for soft deletion instead.
     */
    public function destroy(ExpertiseCategory $expertiseCategory)
    {
        // Warning: This performs a permanent hard delete, which may fail due to foreign key constraints.
        // Consider using the `archive` method above instead.
        $expertiseCategory->delete();

        return redirect()->route('expertise-categories.index')
            ->with('success', 'Expertise category permanently deleted.');
    }
}
