<?php

namespace App\Services;

class CreditCardPaymentService extends GMOPaymentService
{
    const ENTRY_TRAN = '/payment/EntryTran.idPass';
    const EXEC_TRAN  = '/payment/ExecTran.idPass';

    public static function entryTran($orderId, $amount)
    {
        $url = self::retrieveShopUrl(self::ENTRY_TRAN);

        $body = [
            'ShopID'   => config('gmo-payment.shop_id'),
            'ShopPass' => config('gmo-payment.shop_pass'),
            'JobCd'    => config('gmo-payment.job_cd.auth'),
            'OrderID'  => $orderId,
            'Amount'   => $amount,
        ];

        return self::execute($url, $body);
    }

    public static function execTran($data = [])
    {
        $url = self::retrieveShopUrl(self::EXEC_TRAN);

        $body = [
            'AccessID'   => $data['AccessID'],
            'AccessPass' => $data['AccessPass'],
            'OrderID'    => $data['OrderID'],
            'Method'     => 1,
            'SiteID'     => config('gmo-payment.site_id'),
            'SitePass'   => config('gmo-payment.site_pass'),
            'MemberID'   => $data['MemberID'],
            'CardSeq'    => $data['CardSeq'],
        ];

        return self::execute($url, $body);
    }
}