<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Domain\Ledger\Entity\Transaction;
use Domain\Ledger\Repositories\TransactionRepositoryInterface;
use Infrastructure\Persistence\Eloquent\TransactionModel;
use Infrastructure\Persistence\Mappers\TransactionMapper;

final class TransactionEloquentRepository implements TransactionRepositoryInterface
{
    public function save(Transaction $transaction): void
    {
        $model = TransactionModel::find($transaction->id()) ?? new TransactionModel();

        TransactionMapper::toModel($transaction, $model);
        $model->save();
    }

    public function findById(string $id): ?Transaction
    {
        $model = TransactionModel::find($id);

        if (! $model) {
            return null;
        }

        return TransactionMapper::toDomain($model);
    }
}
