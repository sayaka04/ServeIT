<?php

namespace App\Http\Controllers\Table;

use App\Models\TechnicianFile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnicianFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Step 1: Validate the incoming request
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file_description' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'technician_id' => 'required|exists:technicians,id'
        ]);

        // Get the file from the request
        $file = $request->file('file');

        // Step 2: Store the file in the 'public/technician_files' directory
        // The storage path is automatically created if it doesn't exist
        $filePath = $file->store('technician_files', 'public');

        // Remove the 'public/' prefix to store the path correctly in the database
        $dbPath = str_replace('public/', '', $filePath);

        // Get the file type from the file's extension
        $fileType = $file->getClientOriginalExtension();

        // Step 3: Create a new record in the database
        TechnicianFile::create([
            'technician_id' => $request->input('technician_id'),
            'file_name' => $request->input('file_name'),
            'file_description' => $request->input('file_description'),
            'file_path' => $dbPath,
            'file_type' => $fileType,
            'is_deleted' => 0,
        ]);

        // Redirect or return a success response
        return redirect()->back()->with('success', 'File has been uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TechnicianFile $technicianFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TechnicianFile $technicianFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TechnicianFile $technicianFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TechnicianFile $technicianFile)
    {
        $technicianFile->update(['is_deleted' => 1]);
        return back()->with('success', 'File deleted successfully!');
    }
}
