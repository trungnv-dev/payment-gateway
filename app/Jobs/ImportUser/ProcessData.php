<?php

namespace App\Jobs\ImportUser;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProcessData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $failOnTimeout = true;

    public $tries = 1;

    protected $dataImport;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $dataImport)
    {
        $this->dataImport = $dataImport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataInsert = [];

        try {
            // Validate
            foreach ($this->dataImport as $data) {
                $validator = Validator::make($data, [
                    'name'  => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                ]);

                if ($validator->fails()) {
                    continue;
                } else {
                    $data['role']     = 3;
                    $data['password'] = Hash::make('123456');
                    $dataInsert[]     = $data;
                }
            }

            // Bulk insert
            User::insert($dataInsert);
        } catch (\Exception $e) {
            logger()->error("ERROR Process Data: " . $e->getMessage());
            return;
        }
    }
}
