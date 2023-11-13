<?php

namespace App\Jobs\ImportUser;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $timeout = 120;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // ini_set('memory_limit', '-1');
        try {
            $count = $line = 0;
            $dataImport = [];
            $file = fopen(storage_path('app/public/' . $this->path), 'r');
            while (($data = fgetcsv($file, 0, ',')) !== false) {
                if ($line == 0) { // line header
                    $line++;
                    continue;
                }

                $count++;
                $dataImport[] = [
                    'name'  => $data[0],
                    'email' => $data[1],
                ];

                if ($count == 1000) {
                    $this->batch()->add(new ProcessData($dataImport));
                    $count = 0;
                    $dataImport = [];
                }

                $line++;
            }
            fclose($file);

            if ($count > 0) {
                $this->batch()->add(new ProcessData($dataImport));
            }

            Storage::delete($this->path);
        } catch (\Exception $e) {
            // Storage::delete($this->path);
            logger()->error("ERROR Handle File: " . $e->getMessage());
            return;
        }
    }
}
