<?php

declare(strict_types=1);

namespace Domain\Card\Exceptions;

use DomainException;

final class MccBlockedException extends DomainException
{
    public function __construct(string $message = 'MCC is blocked for this card.')
    {
        parent::__construct($message);
    }
}
