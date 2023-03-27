<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class MediaController extends Controller
{
    public function serveFile($filename)
    {
        $path = public_path("images/" . $filename);
        if (!File::exists($path)) {
            return response(json_encode(["message" => "file not found", "path" => $path]), 400, ['Content-Type' => 'application/json']);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
