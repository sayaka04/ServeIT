<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StorageController extends Controller
{
    public function showFile($filename)
    {

        if (!Auth::user()) {
            abort(401, 'File not found: ' . $filename);
        }

        // Use the 'public' disk that maps to storage/app/public
        $disk = Storage::disk('public');

        if (!$disk->exists($filename)) {
            abort(404, 'File not found: ' . $filename);
        }

        $file = $disk->get($filename);
        $type = $disk->mimeType($filename);

        return response($file, 200)->header("Content-Type", $type);
    }

    // Modified download method to work like showFile2()
    public function downloadRepairOrder($filename)
    {
        if (!Auth::user()) {
            abort(401, 'Unauthorized access to file: ' . $filename);
        }

        // Use the 'public' disk that maps to storage/app/public
        $disk = Storage::disk('public');

        // Check if the file exists
        if (!$disk->exists($filename)) {
            abort(404, 'File not found: ' . $filename);
        }

        // Get the file content
        $file = $disk->get($filename);
        // Get the mime type for the file
        $type = $disk->mimeType($filename);

        // Set Content-Disposition to attachment to trigger file download
        return response($file, 200)
            ->header("Content-Type", $type)
            ->header("Content-Disposition", "attachment; filename=" . basename($filename));
    }




    public function showFile2($filename)
    {
        Log::info("Filename: " . $filename);

        if (!Auth::user()) {
            abort(401, 'File not found: ' . $filename);
        }

        // Use the 'public' disk that maps to storage/app/public
        $disk = Storage::disk('public');

        if (!$disk->exists($filename)) {
            abort(404, 'File not found: ' . $filename);
        } else {
            // Log file existence for debugging
            Log::info("File found: " . $filename);
        }

        $file = $disk->get($filename);
        $type = $disk->mimeType($filename);

        return response($file, 200)->header("Content-Type", $type);
    }
}
