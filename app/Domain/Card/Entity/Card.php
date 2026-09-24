<?php

declare(strict_types=1);

namespace Domain\Card\Entity;

use Domain\Card\Exceptions\AmountExceedsPurchaseLimitException;
use Domain\Card\Exceptions\CardIsBlockedException;
use Domain\Card\Exceptions\InvalidCardAttributeException;
use Domain\Card\Exceptions\MccBlockedException;
use Domain\Shared\ValueObjects\Money;

final class Card
{
    private bool $isActive;

    /** @var array<string> */
    private array $blockedMccs;

    /**
     * @param  array<string>  $blockedMccs
     */
    public function __construct(
        private readonly string $id,
        private readonly string $companyId,
        private readonly Money $monthlyLimit,
        private readonly Money $maxPerPurchase,
        array $blockedMccs = [],
        bool $isActive = true
    ) {
        $this->validate($id, $companyId, $monthlyLimit, $maxPerPurchase);
        $this->blockedMccs = $blockedMccs;
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

    public function monthlyLimit(): Money
    {
        return $this->monthlyLimit;
    }

    public function maxPerPurchase(): Money
    {
        return $this->maxPerPurchase;
    }

    /**
     * @return array<string>
     */
    public function blockedMccs(): array
    {
        return $this->blockedMccs;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function block(): void
    {
        $this->isActive = false;
    }

    public function unblock(): void
    {
        $this->isActive = true;
    }

    public function validatePurchase(Money $amount, string $mcc): void
    {
        if (! $this->isActive) {
            throw new CardIsBlockedException();
        }

        if (in_array($mcc, $this->blockedMccs, true)) {
            throw new MccBlockedException();
        }

        if ($amount->isGreaterThan($this->maxPerPurchase)) {
            throw new AmountExceedsPurchaseLimitException();
        }
    }

    private function validate(string $id, string $companyId, Money $monthlyLimit, Money $maxPerPurchase): void
    {
        if (trim($id) === '') {
            throw new InvalidCardAttributeException('Card ID cannot be empty.');
        }

        if (trim($companyId) === '') {
            throw new InvalidCardAttributeException('Company ID cannot be empty.');
        }

        if ($monthlyLimit->toCents() < 0) {
            throw new InvalidCardAttributeException('Monthly limit cannot be negative.');
        }

        if ($maxPerPurchase->toCents() < 0) {
            throw new InvalidCardAttributeException('Max per purchase cannot be negative.');
        }
    }
}
