<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'filename' => 'required|string',
        ]);

        $imageParts = explode(";base64,", $request->image);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);

        $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $request->filename);
        if (!str_ends_with($filename, '.png')) {
            $filename .= '.png';
        }

        return Response::make($imageBase64, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}
