<?php

namespace App\Http\Controllers\Table;

use App\Models\ReportCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportCategoryController extends Controller
{
    public function index()
    {
        $categories = ReportCategory::all();
        return view('report_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('report_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:report_categories,name',
            'description' => 'nullable|string',
        ]);

        ReportCategory::create($request->all());
        return redirect()->route('report-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(ReportCategory $reportCategory)
    {
        return view('report_categories.show', compact('reportCategory'));
    }

    public function edit(ReportCategory $reportCategory)
    {
        return view('report_categories.edit', compact('reportCategory'));
    }

    public function update(Request $request, ReportCategory $reportCategory)
    {
        $request->validate([
            'name' => 'required|unique:report_categories,name,' . $reportCategory->id,
            'description' => 'nullable|string',
        ]);

        $reportCategory->update($request->all());
        return redirect()->route('report-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ReportCategory $reportCategory)
    {
        $reportCategory->delete();
        return redirect()->route('report-categories.index')->with('success', 'Category deleted successfully.');
    }
}
