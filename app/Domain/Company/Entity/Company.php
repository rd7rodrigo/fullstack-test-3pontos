<?php

declare(strict_types=1);

namespace Domain\Company\Entity;

use Domain\Company\Exceptions\InsufficientCompanyBalanceException;
use Domain\Company\Exceptions\InvalidCompanyAttributeException;
use Domain\Shared\ValueObjects\Money;

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
            throw new InvalidCompanyAttributeException('Deposit amount must be greater than zero.');
        }

        $this->balance = $this->balance->add($amount);
    }

    public function debit(Money $amount): void
    {
        if ($amount->toCents() <= 0) {
            throw new InvalidCompanyAttributeException('Amount to debit must be greater than zero.');
        }

        if ($this->balance->isLessThan($amount)) {
            throw new InsufficientCompanyBalanceException();
        }

        $this->balance = $this->balance->subtract($amount);
    }

    private function validate(string $id, string $name, Money $balance): void
    {
        if (trim($id) === '') {
            throw new InvalidCompanyAttributeException('Company ID cannot be empty.');
        }

        if (trim($name) === '') {
            throw new InvalidCompanyAttributeException('Company name cannot be empty.');
        }

        if ($balance->toCents() < 0) {
            throw new InvalidCompanyAttributeException('Company balance cannot be negative.');
        }
    }
}
