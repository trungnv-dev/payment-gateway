<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            fputcsv($file, ['Name', 'Email']);
            for ($i = 1; $i <= 1000000; $i++) {
                fputcsv($file, ["Trung$i", "nguyenvantrung$i@gmail.com"]);
            }
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
    public function copy()
    {
        $stream = Storage::readStream('stream/export (1).csv');
        Storage::writeStream('stream/copy.csv', $stream);
        fclose($stream);
//        return response()->streamDownload(function () {
//            $file = fopen('php://output', 'w');
//
//            fputcsv($file, ['Name', 'Email']);
//            for ($i = 1; $i <= 10000000; $i++) {
//                fputcsv($file, ["Trung$i", "nguyenvantrung$i@gmail.com"]);
//            }
//            fclose($file);
//        }, 'export.csv', [
//            'Content-Type' => 'text/csv'
//        ]);
    }
}
