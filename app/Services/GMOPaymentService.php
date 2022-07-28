<?php

namespace App\Services;

use App\Enums\GMOPayment;
use GuzzleHttp\Client;

class GMOPaymentService
{
    public static function retrieveShopUrl($path = null)
    {
        return GMOPayment::GMO_URL_DEV . $path;
    }

    public static function retrieveBodySite()
    {
        return [
            'SiteID'   => GMOPayment::SITE_ID,
            'SitePass' => GMOPayment::SITE_PASS,
        ];
    }

    public static function retrieveBodyShop()
    {
        return [
            'ShopID'   => GMOPayment::SHOP_ID,
            'ShopPass' => GMOPayment::SHOP_PASS,
        ];
    }

    public static function execute($url, $body)
    {
        $client = new Client();

        $response = $client->request('POST', $url,
            [
                'form_params' => $body
            ]
        );

        return self::properParseStr($response->getBody()->getContents());
    }

    public static function properParseStr($str)
    {
        # result array
        $arr = [];

        # split on outer delimiter
        $pairs = explode('&', $str);

        # loop through each pair
        foreach ($pairs as $i) {
            # split into name and value
            list($name, $value) = explode('=', $i, 2);

            # if name already exists
            if (isset($arr[$name])) {
                # stick multiple values into an array
                if (is_array($arr[$name])) {
                    $arr[$name][] = $value;
                } else {
                    $arr[$name] = array($arr[$name], $value);
                }
            } # otherwise, simply stick it in a scalar
            else {
                $arr[$name] = $value;
            }
        }

        return $arr;
    }
}