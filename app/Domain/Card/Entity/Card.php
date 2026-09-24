<?php

declare(strict_types=1);

namespace Domain\Card\Entity;

use Domain\Shared\ValueObjects\Money;
use InvalidArgumentException;

final class Card
{
    private bool $isActive;

    public function __construct(
        private readonly string $id,
        private readonly string $companyId,
        private Money $balance,
        bool $isActive = true
    ) {
        $this->validate($id, $companyId, $balance);
        $this->isActive = $isActive;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function companyId(): string
    {
        return $this->companyId;
    }

    public function balance(): Money
    {
        return $this->balance;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function credit(Money $amount): void
    {
        if ($amount->toCents() <= 0) {
            throw new InvalidArgumentException('Credit amount must be greater than zero.');
        }

        $this->balance = $this->balance->add($amount);
    }

    public function debit(Money $amount): void
    {
        if ($amount->toCents() <= 0) {
            throw new InvalidArgumentException('Debit amount must be greater than zero.');
        }

        if ($this->balance->isLessThan($amount)) {
            throw new InvalidArgumentException('Insufficient funds on card.');
        }

        $this->balance = $this->balance->subtract($amount);
    }

    public function block(): void
    {
        $this->isActive = false;
    }

    public function unblock(): void
    {
        $this->isActive = true;
    }

    private function validate(string $id, string $companyId, Money $balance): void
    {
        if (trim($id) === '') {
            throw new InvalidArgumentException('Card ID cannot be empty.');
        }

        if (trim($companyId) === '') {
            throw new InvalidArgumentException('Company ID cannot be empty.');
        }

        if ($balance->toCents() < 0) {
            throw new InvalidArgumentException('Initial balance cannot be negative.');
        }
    }
}
