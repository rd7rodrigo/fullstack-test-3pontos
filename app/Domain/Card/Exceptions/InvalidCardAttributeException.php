<?php

declare(strict_types=1);

namespace Domain\Card\Exceptions;

use InvalidArgumentException;

final class InvalidCardAttributeException extends InvalidArgumentException
{
    public function __construct(string $message = 'Invalid card attribute.')
    {
        parent::__construct($message);
    }
}
