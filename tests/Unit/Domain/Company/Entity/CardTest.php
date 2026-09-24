<?php

declare(strict_types=1);

use Domain\Card\Entity\Card;
use Domain\Shared\ValueObjects\Money;

// Testa se o cartão é criado corretamente com saldo inicial e status ativo
test('it can instantiate a card with valid attributes', function () {
    $initialBalance = Money::fromCents(5000); // R$ 50,00

    $card = new Card(
        id: 'card_789',
        companyId: 'comp_123',
        balance: $initialBalance
    );

    expect($card->id())->toBe('card_789')
        ->and($card->companyId())->toBe('comp_123')
        ->and($card->balance()->equals($initialBalance))->toBeTrue()
        ->and($card->isActive())->toBeTrue();
});

// Testa se lança exceção quando o ID do cartão é vazio ou em branco
test('it throws exception when card id is empty or blank', function () {
    expect(fn () => new Card(id: '', companyId: 'comp_123', balance: Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Card ID cannot be empty.');

    expect(fn () => new Card(id: '   ', companyId: 'comp_123', balance: Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Card ID cannot be empty.');
});

// Testa se lança exceção quando o ID da empresa é vazio ou em branco
test('it throws exception when company id is empty or blank', function () {
    expect(fn () => new Card(id: 'card_789', companyId: '', balance: Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Company ID cannot be empty.');

    expect(fn () => new Card(id: 'card_789', companyId: '   ', balance: Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Company ID cannot be empty.');
});

// Testa se lança exceção quando o saldo inicial é negativo
test('it throws exception when initial balance is negative', function () {
    expect(fn () => new Card(id: 'card_789', companyId: 'comp_123', balance: Money::fromCents(-100)))
        ->toThrow(InvalidArgumentException::class, 'Initial balance cannot be negative.');
});

// Testa se é possível creditar (adicionar saldo) ao cartão com valor válido
test('it can credit amount to the card balance', function () {
    $card = new Card(
        id: 'card_789',
        companyId: 'comp_123',
        balance: Money::fromCents(1000)
    );

    $card->credit(Money::fromCents(2500));

    expect($card->balance()->toCents())->toBe(3500);
});

// Testa se lança exceção ao tentar creditar um valor zero ou negativo
test('it throws exception when crediting invalid or non-positive amount', function () {
    $card = new Card(id: 'card_789', companyId: 'comp_123', balance: Money::fromCents(1000));

    expect(fn () => $card->credit(Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Credit amount must be greater than zero.');

    expect(fn () => $card->credit(Money::fromCents(-500)))
        ->toThrow(InvalidArgumentException::class, 'Credit amount must be greater than zero.');
});

// Testa se é possível debitar saldo do cartão quando há fundos suficientes
test('it can debit amount from the card balance when sufficient funds exist', function () {
    $card = new Card(
        id: 'card_789',
        companyId: 'comp_123',
        balance: Money::fromCents(5000)
    );

    $card->debit(Money::fromCents(2000));

    expect($card->balance()->toCents())->toBe(3000);
});

// Testa se lança exceção ao tentar debitar valor superior ao saldo disponível
test('it throws exception when debiting amount greater than available balance', function () {
    $card = new Card(
        id: 'card_789',
        companyId: 'comp_123',
        balance: Money::fromCents(1000)
    );

    expect(fn () => $card->debit(Money::fromCents(1500)))
        ->toThrow(InvalidArgumentException::class, 'Insufficient funds on card.');
});

// Testa se lança exceção ao tentar debitar valor zero ou negativo
test('it throws exception when debiting invalid or non-positive amount', function () {
    $card = new Card(id: 'card_789', companyId: 'comp_123', balance: Money::fromCents(1000));

    expect(fn () => $card->debit(Money::fromCents(0)))
        ->toThrow(InvalidArgumentException::class, 'Debit amount must be greater than zero.');

    expect(fn () => $card->debit(Money::fromCents(-200)))
        ->toThrow(InvalidArgumentException::class, 'Debit amount must be greater than zero.');
});

// Testa se o cartão pode ser bloqueado e desbloqueado corretamente
test('it can be blocked and unblocked', function () {
    $card = new Card(
        id: 'card_789',
        companyId: 'comp_123',
        balance: Money::fromCents(1000)
    );

    expect($card->isActive())->toBeTrue();

    $card->block();
    expect($card->isActive())->toBeFalse();

    $card->unblock();
    expect($card->isActive())->toBeTrue();
});
