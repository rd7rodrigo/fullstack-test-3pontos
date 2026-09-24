<?php

declare(strict_types=1);

namespace Domain\Company\Exceptions;

use DomainException;

final class InsufficientCompanyBalanceException extends DomainException
{
    public function __construct(string $message = 'Insufficient company balance.')
    {
        parent::__construct($message);
    }
}
