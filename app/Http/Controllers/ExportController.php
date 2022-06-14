<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index()
    {
        $users = User::select('name', 'email')->get();

        $filename = "2.csv";

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Name', 'Email']);
            
            foreach ($users as $value) {
                $data = $value->toArray();
                
                fputcsv($file, $data);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);
    }
}
