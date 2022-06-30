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
    protected $signature = 'scrape:tgdd';

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
        echo "Start Crawler Data TGDD \n";
        $bot = new \App\Scraper\TGDD();
        $bot->scrape();
        echo "End Crawler Data TGDD \n";
    }
}
