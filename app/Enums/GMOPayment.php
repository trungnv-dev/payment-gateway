<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GMOPayment extends Enum
{
    const SITE_ID               = 'tsite00048307';
    const SITE_PASS             = '5mdakt8x';
    const SHOP_ID               = 'tshop00056930';
    const SHOP_PASS             = '7aytd52d';

    const GMO_URL_DEV           = 'https://pt01.mul-pay.jp';
    const GMO_URL_STG           = '';

    const JOB_CD_CAPTURE        = 'CAPTURE';
    const JOB_CD_AUTH           = 'AUTH';
    const JOB_CD_CANCEL         = 'CANCEL';
    const JOB_CD_SALES          = 'SALES';

    const EXEC_BULK             = 1;
    const EXEC_INSTALLMENT      = 2;

    const TRAN_STT_UNPAID       = 'UNPAID';
    const TRAN_STT_PAID         = 'PAID';

    const STT_SECURE            = [
        0 => 'No 3DS',
        1 => '3DS.1',
        2 => '3DS.2',
    ];

    const STATUS_PAID           = [
        "CAPTURE"               => "CAPTURE",
        "AUTH"                  => "AUTH",
        "SALES"                 => "SALES",
    ];
    const STATUS_UNPAID         = [
        "UNPROCESSED"           => "UNPROCESSED",
        "AUTHENTICATED"         => "AUTHENTICATED",
    ];
    const STATUS_CANCEL         = [
        "VOID"                  => "VOID",
        "RETURN"                => "RETURN",
    ];
}
