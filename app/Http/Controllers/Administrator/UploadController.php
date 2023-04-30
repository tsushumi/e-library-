<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = $file->getClientOriginalName();
            $folder = uniqid() . '-' . now()->timestamp;
            $file->storeAs('public/uploads/tmp/' . $folder, $filename);

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);

            return $folder;
        }
        return '';
    }

    public function revert(Request $request)
    {
        $temporaryFile = TemporaryFile::where('folder', $request->get('cover'))->first();
        if ($temporaryFile) {
            $tempPath = storage_path('app/public/uploads/tmp/' . $request->get('cover'));
            $tempFile = storage_path('app/public/uploads/tmp/' . $request->get('cover') . '/' . $temporaryFile->filename);
            $temporaryFile->delete();
            if (is_dir($tempPath)) {
                unlink($tempFile);
                rmdir($tempPath);
            }
        }
        $this->createAlert('Success', 'Image reverted successfully', 'danger');
    }
}
