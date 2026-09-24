<?php

declare(strict_types=1);

namespace Domain\Card\Exceptions;

use DomainException;

final class CardIsBlockedException extends DomainException
{
    public function __construct(string $message = 'Card is blocked.')
    {
        parent::__construct($message);
    }
}
