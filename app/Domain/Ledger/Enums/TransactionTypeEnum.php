<?php

declare(strict_types=1);

namespace Domain\Ledger\Enums;

enum TransactionTypeEnum: string
{
    case RESERVE = 'reserve';
    case RELEASE = 'release';
    case CAPTURE = 'capture';
    case CANCELLATION = 'cancellation';
}
