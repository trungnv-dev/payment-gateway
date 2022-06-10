<?php

namespace App\Services;

class CardService extends GMOPaymentService
{
    const SAVE_CARD           = '/payment/SaveCard.idPass';
    const SEARCH_CARD         = '/payment/SearchCard.idPass';
    const SEARCH_CARD_DETAIL  = '/payment/SearchCardDetail.idPass';
    const DELETE_CARD         = '/payment/DeleteCard.idPass';

    /**
     * @param $memberId
     * @param $cardNo
     * @param $expire
     * @return CardSeq & CardNo & Forward || ErrCode & ErrInfo
    */
    public static function saveCard($memberId, $cardNo, $expire)
    {
        $url = self::retrieveShopUrl(self::SAVE_CARD);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID' => $memberId,
            'CardNo'   => $cardNo,
            'Expire'   => $expire,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @return list card || ErrCode & ErrInfo
    */
    public static function searchCard($memberId)
    {
        $url = self::retrieveShopUrl(self::SEARCH_CARD);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID' => $memberId,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @return card || ErrCode & ErrInfo
    */
    public static function searchCardDetail($memberId)
    {
        $url = self::retrieveShopUrl(self::SEARCH_CARD_DETAIL);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID' => $memberId,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @param $cardSeq
     * @return card || ErrCode & ErrInfo
    */
    public static function deleteCard($memberId, $cardSeq)
    {
        $url = self::retrieveShopUrl(self::DELETE_CARD);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID' => $memberId,
            'CardSeq'  => $cardSeq,
        ]);

        return self::execute($url, $body);
    }
}