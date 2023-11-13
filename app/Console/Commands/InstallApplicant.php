<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallApplicant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install project';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start ------> ');

        $this->call('migrate:fresh', ['--seed' => true]);
        $this->info('Migrated and seeded all data.');

        $this->call('passport:install', ['--force' => true]);
        $this->info('Installed Laravel Passport.');

        $this->call('scrape:tgdd', ['--remind' => true]);
        $this->info('Crawl data for products.');

        $this->info('Completed!');

        return true;
    }
}
