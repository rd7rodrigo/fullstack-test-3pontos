<?php

declare(strict_types=1);

use Domain\Card\Entity\Card;
use Domain\Card\Exceptions\InvalidCardAttributeException;
use Domain\Shared\ValueObjects\Money;

// Testa se o cartão pode ser instanciado corretamente com suas propriedades contratuais
test('it can instantiate a card with valid attributes', function () {
    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000), // R$ 2.000,00
        maxPerPurchase: Money::fromCents(80000),  // R$ 800,00
        blockedMccs: ['7995'],
        isActive: true
    );

    expect($card->id())->toBe('tok_ana')
        ->and($card->companyId())->toBe('comp_acme')
        ->and($card->monthlyLimit()->toCents())->toBe(200000)
        ->and($card->maxPerPurchase()->toCents())->toBe(80000)
        ->and($card->blockedMccs())->toBe(['7995'])
        ->and($card->isActive())->toBeTrue();
});

// Testa se o cartão pode ser bloqueado e desbloqueado corretamente
test('it can be blocked and unblocked', function () {
    $card = new Card(
        id: 'tok_carla',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(100000),
        maxPerPurchase: Money::fromCents(50000),
        blockedMccs: [],
        isActive: true
    );

    expect($card->isActive())->toBeTrue();

    $card->block();
    expect($card->isActive())->toBeFalse();

    $card->unblock();
    expect($card->isActive())->toBeTrue();
});

// Testa se lança exceção quando o ID do cartão está vazio
test('it throws exception when card id is empty', function () {
    expect(fn () => new Card(
        id: '',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(100000),
        maxPerPurchase: Money::fromCents(50000)
    ))->toThrow(InvalidCardAttributeException::class, 'Card ID cannot be empty.');
});

// Testa se lança exceção quando o ID da empresa está vazio
test('it throws exception when company id is empty on card', function () {
    expect(fn () => new Card(
        id: 'tok_ana',
        companyId: '',
        monthlyLimit: Money::fromCents(100000),
        maxPerPurchase: Money::fromCents(50000)
    ))->toThrow(InvalidCardAttributeException::class, 'Company ID cannot be empty.');
});

// Testa se lança exceção quando o limite mensal é negativo
test('it throws exception when monthly limit is negative', function () {
    expect(fn () => new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(-100),
        maxPerPurchase: Money::fromCents(50000)
    ))->toThrow(InvalidCardAttributeException::class, 'Monthly limit cannot be negative.');
});

// Testa se lança exceção quando o teto por compra é negativo
test('it throws exception when max per purchase is negative', function () {
    expect(fn () => new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(100000),
        maxPerPurchase: Money::fromCents(-50)
    ))->toThrow(InvalidCardAttributeException::class, 'Max per purchase cannot be negative.');
});
