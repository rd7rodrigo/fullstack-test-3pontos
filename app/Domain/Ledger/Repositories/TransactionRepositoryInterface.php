<?php

declare(strict_types=1);

namespace Domain\Ledger\Repositories;

use Domain\Ledger\Entity\Transaction;

// Aqui definiremos a estrutura de persistência das transações imutáveis do ledger
interface TransactionRepositoryInterface
{
    public function save(Transaction $transaction): void;

    public function findById(string $id): ?Transaction;
}
