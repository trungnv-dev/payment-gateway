<?php

namespace App\Console\Commands;

use App\Mail\TestSendMail;
use Illuminate\Console\Command;

class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:tgdd {--auth}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl list product TGDD';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->option('auth')) {
            $auth = $this->call('authentication');
            if (!$auth) return;
        }
        $this->info("Start Crawler Data TGDD");
        $bot = new \App\Scraper\TGDD();
        $bot->scrape();
        $this->info("End Crawler Data TGDD");

        \Mail::to(config('app.login_admin_mail'))->queue(new TestSendMail(['timeComplete' => now()], 'Crawler Data TGDD Complete!', 'emails/scrape'));
    }
}
