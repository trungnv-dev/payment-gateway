<?php

namespace App\Services;

class CreditCardPaymentService extends GMOPaymentService
{
    const ENTRY_TRAN    = '/payment/EntryTran.idPass';
    const EXEC_TRAN     = '/payment/ExecTran.idPass';
    const ALTER_TRAN    = '/payment/AlterTran.idPass';
    const SECURE_TRAN   = '/payment/SecureTran.idPass';
    const SECURE_TRAN2  = '/payment/SecureTran2.idPass';
    const SEARCH_TRADE  = '/payment/SearchTrade.idPass';

    /**
     * @return AccessID & AccessPass || ERROR
    */
    public static function entryTran($body = [])
    {
        $url = self::retrieveShopUrl(self::ENTRY_TRAN);

        $body = array_merge(self::retrieveBodyShop(), $body);

        return self::execute($url, $body);
    }

    /**
     * @return ACS & OrderID & Forward & PayTimes & Approve & TranID & TranDate || ERROR
    */
    public static function execTran($body = [])
    {
        $url = self::retrieveShopUrl(self::EXEC_TRAN);

        $body = array_merge(self::retrieveBodySite(), $body);

        return self::execute($url, $body);
    }

    /**
     * @return AccessID & AccessPass & Forward & Approve & TranID & TranDate || ERROR
    */
    public static function alterTran($body = [])
    {
        $url = self::retrieveShopUrl(self::ALTER_TRAN);

        $body = array_merge(self::retrieveBodyShop(), $body);

        return self::execute($url, $body);
    }

    /**
     * @return OrderID & Forward & Method & PayTimes & Approve & TranID & TranDate & CheckString || ERROR
    */
    public static function secureTran($body = [])
    {
        $url = self::retrieveShopUrl(self::SECURE_TRAN);

        return self::execute($url, $body);
    }

    /**
     * @return OrderID & Forward & Method & PayTimes & Approve & TranID & TranDate & CheckString || ERROR
    */
    public static function secureTran2($body = [])
    {
        $url = self::retrieveShopUrl(self::SECURE_TRAN2);

        return self::execute($url, $body);
    }

    /**
     * @return Transaction || ERROR
    */
    public static function searchTrade($orderID)
    {
        $url = self::retrieveShopUrl(self::SEARCH_TRADE);

        $body = array_merge(self::retrieveBodyShop(), [
            'OrderID' => $orderID,
        ]);

        return self::execute($url, $body);
    }
}