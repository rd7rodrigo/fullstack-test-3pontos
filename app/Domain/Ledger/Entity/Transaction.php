<?php

declare(strict_types=1);

namespace Domain\Ledger\Entity;

use Domain\Ledger\Enums\TransactionTypeEnum;
use Domain\Shared\ValueObjects\Money;

final class Transaction
{
    public function __construct(
        private readonly string $id,
        private readonly string $cardId,
        private readonly string $companyId,
        private readonly Money $amount,
        private readonly TransactionTypeEnum $type,
        private readonly ?string $referenceId = null
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function cardId(): string
    {
        return $this->cardId;
    }

    public function companyId(): string
    {
        return $this->companyId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function type(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function referenceId(): ?string
    {
        return $this->referenceId;
    }
}
