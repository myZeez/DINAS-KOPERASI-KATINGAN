<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FileDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $files = FileDownload::when($search, function ($query, $search) {
            return $query->search($search);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.file-downloads.index', compact('files', 'search'));
    }

    public function download(FileDownload $fileDownload): BinaryFileResponse
    {
        try {
            $filePath = storage_path('app/public/' . $fileDownload->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'File tidak ditemukan');
            }

            // Increment download count
            $fileDownload->incrementDownloadCount();

            return response()->download($filePath, $fileDownload->original_filename);
        } catch (\Exception $e) {
            abort(500, 'Gagal mengunduh file');
        }
    }
}
