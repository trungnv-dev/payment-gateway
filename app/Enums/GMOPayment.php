<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GMOPayment extends Enum
{
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
