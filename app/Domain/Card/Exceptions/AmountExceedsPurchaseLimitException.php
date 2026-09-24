<?php

declare(strict_types=1);

namespace Domain\Card\Exceptions;

use DomainException;

final class AmountExceedsPurchaseLimitException extends DomainException
{
    public function __construct(string $message = 'Amount exceeds maximum per purchase limit.')
    {
        parent::__construct($message);
    }
}
