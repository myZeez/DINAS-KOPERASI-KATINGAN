<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileDownloadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $files = FileDownload::when($search, function ($query, $search) {
            return $query->search($search);
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.file-downloads.index', compact('files', 'search'));
    }

    public function create()
    {
        return view('admin.file-downloads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:51200' // 50MB max
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('downloads', $fileName, 'public');

                FileDownload::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'file_path' => $filePath,
                    'original_filename' => $originalName,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);

                return redirect()->route('admin.file-downloads.index')
                    ->with('success', 'File berhasil diupload!');
            }

            return back()->with('error', 'File tidak ditemukan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    public function show(FileDownload $fileDownload)
    {
        return view('admin.file-downloads.show', compact('fileDownload'));
    }

    public function edit(FileDownload $fileDownload)
    {
        return view('admin.file-downloads.edit', compact('fileDownload'));
    }

    public function update(Request $request, FileDownload $fileDownload)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:51200'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
            ];

            // If new file is uploaded
            if ($request->hasFile('file')) {
                // Delete old file
                if ($fileDownload->file_path && Storage::disk('public')->exists($fileDownload->file_path)) {
                    Storage::disk('public')->delete($fileDownload->file_path);
                }

                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('downloads', $fileName, 'public');

                $data['file_path'] = $filePath;
                $data['original_filename'] = $originalName;
                $data['file_size'] = $file->getSize();
                $data['mime_type'] = $file->getMimeType();
            }

            $fileDownload->update($data);

            return redirect()->route('admin.file-downloads.index')
                ->with('success', 'File berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui file: ' . $e->getMessage());
        }
    }

    public function destroy(FileDownload $fileDownload)
    {
        try {
            $fileDownload->delete(); // Soft delete
            return redirect()->route('admin.file-downloads.index')
                ->with('success', 'File berhasil dipindahkan ke trash!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    public function trash(Request $request)
    {
        $search = $request->get('search');

        $files = FileDownload::onlyTrashed()
            ->when($search, function ($query, $search) {
                return $query->search($search);
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.file-downloads.trash', compact('files', 'search'));
    }

    public function restore($id)
    {
        try {
            $fileDownload = FileDownload::onlyTrashed()->findOrFail($id);
            $fileDownload->restore();

            return redirect()->route('admin.file-downloads.trash')
                ->with('success', 'File berhasil dipulihkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan file: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $fileDownload = FileDownload::onlyTrashed()->findOrFail($id);

            // Delete file from storage
            if ($fileDownload->file_path && Storage::disk('public')->exists($fileDownload->file_path)) {
                Storage::disk('public')->delete($fileDownload->file_path);
            }

            $fileDownload->forceDelete();

            return redirect()->route('admin.file-downloads.trash')
                ->with('success', 'File berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file permanen: ' . $e->getMessage());
        }
    }
}
