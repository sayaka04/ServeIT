<?php

namespace App\Http\Controllers\Table;

use App\Models\TechnicianLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TechnicianLinkController extends Controller
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
        $request->validate([
            'technician_id' => 'required|exists:technicians,id', // Ensure technician exists
            'url' => 'required|url', // Ensure the URL is a valid URL
        ]);

        // Here, we will infer the type of the link from the URL.
        $url = parse_url($request->url, PHP_URL_HOST);

        // Simple URL type detection based on the hostname (could be extended)
        $type = '';
        if (strpos($url, 'facebook.com') !== false) {
            $type = 'facebook';
        } elseif (strpos($url, 'linkedin.com') !== false) {
            $type = 'linkedin';
        } elseif (strpos($url, 'twitter.com') !== false) {
            $type = 'twitter';
        } else {
            $type = 'other';  // Default type
        }

        // Store the technician link
        TechnicianLink::create([
            'technician_id' => $request->technician_id,
            'url' => $request->url,
            'type' => $type,
            'is_deleted' => false,  // Default to 'not deleted'
        ]);

        return back()->with('success', 'Link added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(TechnicianLink $technicianLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TechnicianLink $technicianLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TechnicianLink $technicianLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TechnicianLink $technicianLink)
    {
        Log::info('Deleting technician link with ID: ' . $technicianLink->id);
        $technicianLink->update(['is_deleted' => 1]);
        return back()->with('success', 'Link deleted successfully!');
    }
}
