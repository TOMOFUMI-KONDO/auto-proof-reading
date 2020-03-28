<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request) {
//        $file_path = '/calibrated/1585386027_konnnitiha.txt';
//        $file_name = '1585386027_konnnitiha.txt';
        $file_path = '/calibrated/' . $request->input('file-name');
        $file_name = $request->input('file-name');
        $mime_type = Storage::disk('local_public')->mimeType($file_path);
        $headers = [['Content-Type' => $mime_type]];

        return Storage::disk('local_public')->download($file_path, $file_name, $headers);
    }
}
