<?php

namespace App\Scraper;

use App\Models\Product;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{
    public function scrape()
    {
        DB::beginTransaction();
        try {
            $url = 'https://www.thegioididong.com/dtdd';

            $client = new Client();

            $crawler = $client->request('GET', $url);

            foreach($crawler->filter('ul.listproduct li.item .main-contain') as $key => $content) {
                $element = new Crawler($content);

                $name = $element->attr("data-name") ?? NULL;

                if (empty($name)) { continue; }

                $price = preg_replace('/\D/', '', $element->attr("data-price")) ?? 0;

                $srcImg = $element->filter('.item-img .thumb')->attr('src') ?? $element->filter('.item-img .thumb')->attr('data-src');

                $srcImg = copy_image($srcImg, 'crawler/tgdd');

                $condition = ['name' => $name];

                $data = [
                    'name'  => $name,
                    'price' => rtrim($price, 0),
                    'src'   => $srcImg,
                ];

                Product::updateOrCreate($condition, $data);
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::error("ERROR CRAWLER DATA TGDD ::" . $e->getMessage());
            DB::rollBack();
        }
    }
}
