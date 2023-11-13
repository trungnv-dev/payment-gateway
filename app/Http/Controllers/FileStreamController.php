<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUser\ProcessFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileStreamController extends Controller
{
    public function index(): View
    {
        return view('file-stream.index');
    }

    /**
     *  Export data large into file doesn't exists.
     */
    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
            // fputs($file, $bom);
            fputcsv($file, ['Name', 'Email']);
            User::chunk(1000, function ($users) use ($file) {
                $users->each(function ($user) use ($file) {
                    fputcsv($file, [$user->name, $user->email]);
                });
            });
            fclose($file);
        }, 'export.csv', [
            'Content-Type' => 'text/csv'
        ]);
    }

    /**
     *  Download file data large exists.
     */
    public function download(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $stream = Storage::readStream('stream/download.csv');
            while(ob_get_level() > 0) ob_end_flush();
            fpassthru($stream);
            fclose($stream);
        }, 'download.csv', [
            'Content-Type'   => 'text/csv',
            'Content-Length' => Storage::size('stream/download.csv'),
        ]);
    }

    /**
     *  Copy file data large from s3 save to local.
     */
    public function copy(): RedirectResponse
    {
        return back();
    }

    /**
     *  Import file data large from local save to database.
     */
    public function importCsv(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv|max:20000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('file');
        $name = $file->hashName(); // Generate a unique, random name...
        $path = Storage::putFileAs('imports', $file, $name);

        $batch = Bus::batch([new ProcessFile($path)])
            ->name('Import Users')
            ->onQueue('import-user')
            ->allowFailures()
            ->dispatch();

        return redirect()->back()->with([
            'batchId' => $batch->id
        ])->withMessage('Imported successfully!');
    }
}
