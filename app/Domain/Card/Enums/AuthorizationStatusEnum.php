<?php

declare(strict_types=1);

namespace Domain\Card\Enums;

enum AuthorizationStatusEnum: string
{
    case APPROVED = 'APPROVED';
    case DECLINED = 'DECLINED';
}
