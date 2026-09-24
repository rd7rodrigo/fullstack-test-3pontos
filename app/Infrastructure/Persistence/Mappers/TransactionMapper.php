<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Mappers;

use Domain\Ledger\Entity\Transaction;
use Domain\Ledger\Enums\TransactionTypeEnum;
use Domain\Shared\ValueObjects\Money;
use Infrastructure\Persistence\Eloquent\TransactionModel;

final class TransactionMapper
{
    public static function toDomain(TransactionModel $model): Transaction
    {
        return new Transaction(
            id: $model->id,
            cardId: $model->card_id,
            companyId: $model->company_id,
            amount: Money::fromCents($model->amount),
            type: TransactionTypeEnum::from($model->type),
            referenceId: $model->reference_id
        );
    }

    public static function toModel(Transaction $transaction, TransactionModel $model): void
    {
        $model->id = $transaction->id();
        $model->card_id = $transaction->cardId();
        $model->company_id = $transaction->companyId();
        $model->amount = $transaction->amount()->toCents();
        $model->type = $transaction->type()->value;
        $model->reference_id = $transaction->referenceId();
    }
}
