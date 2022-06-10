<?php

namespace App\Services;

class MemberService extends GMOPaymentService
{
    const SAVE_MEMBER    = '/payment/SaveMember.idPass';
    const SEARCH_MEMBER  = '/payment/SearchMember.idPass';
    const UPDATE_MEMBER  = '/payment/UpdateMember.idPass';
    const DELETE_MEMBER  = '/payment/DeleteMember.idPass';

    /**
     * @param $memberId
     * @param NULL $memberName
     * @return $memberId || ErrCode & ErrInfo
    */
    public static function saveMember($memberId, $memberName = NULL)
    {
        $url = self::retrieveShopUrl(self::SAVE_MEMBER);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID'     => $memberId,
            'MemberName'   => $memberName,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @return MemberID & MemberName & DeleteFlag || ErrCode & ErrInfo
    */
    public static function searchMember($memberId)
    {
        $url = self::retrieveShopUrl(self::SEARCH_MEMBER);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID'     => $memberId,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @param NULL $memberName
     * @return $memberId || ErrCode & ErrInfo
    */
    public static function updateMember($memberId, $memberName = NULL)
    {
        $url = self::retrieveShopUrl(self::UPDATE_MEMBER);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID'     => $memberId,
            'MemberName'   => $memberName,
        ]);

        return self::execute($url, $body);
    }

    /**
     * @param $memberId
     * @return $memberId || ErrCode & ErrInfo
    */
    public static function deleteMember($memberId)
    {
        $url = self::retrieveShopUrl(self::DELETE_MEMBER);

        $body = array_merge(self::retrieveBodySite(), [
            'MemberID'     => $memberId,
        ]);

        return self::execute($url, $body);
    }
}