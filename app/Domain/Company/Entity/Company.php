<?php

declare(strict_types=1);

namespace Domain\Company\Entity;

use Domain\Shared\ValueObjects\Money;
use InvalidArgumentException;

final class Company
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private Money $balance
    ) {
        $this->validate($id, $name, $balance);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function balance(): Money
    {
        return $this->balance;
    }

    public function credit(Money $amount): void
    {
        if ($amount->toCents() <= 0) {
            throw new InvalidArgumentException('Deposit amount must be greater than zero.');
        }

        $this->balance = $this->balance->add($amount);
    }

    public function debit(Money $amount): void
    {
        if ($amount->toCents() <= 0) {
            throw new InvalidArgumentException('Amount to debit must be greater than zero.');
        }

        if ($this->balance->isLessThan($amount)) {
            throw new InvalidArgumentException('Insufficient company balance.');
        }

        $this->balance = $this->balance->subtract($amount);
    }

    private function validate(string $id, string $name): void
    {
        if (trim($id) === '') {
            throw new InvalidArgumentException('Company ID cannot be empty.');
        }

        if (trim($name) === '') {
            throw new InvalidArgumentException('Company name cannot be empty.');
        }

        if ($this->balance->toCents() < 0) {
            throw new InvalidArgumentException('Company balance cannot be negative.');
        }
    }
}
