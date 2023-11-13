<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:tgdd {--remind}';

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
        if (!$this->option('remind')) {
            $auth = $this->call('authentication');
            if (!$auth) return false;
        }
        $this->info(now() . ":: Start Crawler Data TGDD");
        $bot = new \App\Scraper\TGDD();
        $bot->scrape();
        $timeEnd = now();
        $this->alert("Crawler Data TGDD Complete!");
        $this->alert("Time complete: $timeEnd");
        $this->info("$timeEnd:: End Crawler Data TGDD");

        return true;
    }
}
