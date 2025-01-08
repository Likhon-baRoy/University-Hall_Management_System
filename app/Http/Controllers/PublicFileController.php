<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PublicFileController extends Controller
{
  public function showRoomPhoto($filename)
  {
    try {
      // Define the path
      $path = 'image/room/' . $filename;

      // If file doesn't exist, try to return default image
      if (!Storage::disk('public')->exists($path)) {
        $path = 'image/room/default-room.jpg';

        // If even default doesn't exist, return 404
        if (!Storage::disk('public')->exists($path)) {
          return response()->json(['error' => 'Image not found'], 404);
        }
      }

      // Get the file
      $file = Storage::disk('public')->get($path);

      // Determine MIME type
      $mimeType = Storage::disk('public')->mimeType($path);

      // Return the file with appropriate headers
      return response($file)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400');

    } catch (\Exception $e) {
      // Log the error
      \Log::error('Room photo error: ' . $e->getMessage());
      return response()->json(['error' => 'Failed to load image'], 500);
    }
  }
}
