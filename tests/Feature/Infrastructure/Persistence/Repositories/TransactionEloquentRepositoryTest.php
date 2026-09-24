<?php

declare(strict_types=1);

use Domain\Card\Entity\Card;
use Domain\Company\Entity\Company;
use Domain\Ledger\Entity\Transaction;
use Domain\Ledger\Enums\TransactionTypeEnum;
use Domain\Shared\ValueObjects\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Infrastructure\Persistence\Repositories\CardEloquentRepository;
use Infrastructure\Persistence\Repositories\CompanyEloquentRepository;
use Infrastructure\Persistence\Repositories\TransactionEloquentRepository;

uses(RefreshDatabase::class);

/**
 * Garante que a transação do ledger é persistida corretamente e pode ser recuperada por ID.
 */
test('[TransactionEloquentRepository] it can save and find a transaction by id', function () {
    // Configurando dependências (Empresa e Cartão para atender às chaves estrangeiras)
    $companyRepository = new CompanyEloquentRepository();
    $companyRepository->save(new Company('comp_acme', 'Acme Corp', Money::fromCents(1000000)));

    $cardRepository = new CardEloquentRepository();
    $cardRepository->save(new Card('tok_ana', 'comp_acme', Money::fromCents(200000), Money::fromCents(50000), [], true));

    $repository = new TransactionEloquentRepository();

    $transaction = new Transaction(
        id: 'tx_123',
        cardId: 'tok_ana',
        companyId: 'comp_acme',
        amount: Money::fromCents(15000),
        type: TransactionTypeEnum::RESERVE,
        referenceId: null
    );

    $repository->save($transaction);

    $foundTransaction = $repository->findById('tx_123');

    expect($foundTransaction)->not->toBeNull()
        ->and($foundTransaction->id())->toBe('tx_123')
        ->and($foundTransaction->cardId())->toBe('tok_ana')
        ->and($foundTransaction->companyId())->toBe('comp_acme')
        ->and($foundTransaction->amount()->toCents())->toBe(15000)
        ->and($foundTransaction->type())->toBe(TransactionTypeEnum::RESERVE)
        ->and($foundTransaction->referenceId())->toBeNull();
});

/**
 * Garante que o repositório retorna null ao buscar uma transação inexistente.
 */
test('[TransactionEloquentRepository] it returns null when transaction is not found', function () {
    $repository = new TransactionEloquentRepository();

    $foundTransaction = $repository->findById('invalid_tx');

    expect($foundTransaction)->toBeNull();
});
