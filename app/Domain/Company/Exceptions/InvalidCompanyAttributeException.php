<?php

declare(strict_types=1);

namespace Domain\Company\Exceptions;

use InvalidArgumentException;

final class InvalidCompanyAttributeException extends InvalidArgumentException
{
    public function __construct(string $message = 'Invalid company attribute.')
    {
        parent::__construct($message);
    }
}
