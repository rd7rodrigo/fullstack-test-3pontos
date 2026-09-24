<?php

declare(strict_types=1);

use Domain\Card\Entity\Card;
use Domain\Card\Exceptions\AmountExceedsPurchaseLimitException;
use Domain\Card\Exceptions\CardIsBlockedException;
use Domain\Card\Exceptions\MccBlockedException;
use Domain\Shared\ValueObjects\Money;

// Testa se permite a compra quando o cartão está ativo, dentro do teto, abaixo do limite e com MCC permitido
test('it allows purchase when all business rules are respected', function () {
    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000), // R$ 2.000,00
        maxPerPurchase: Money::fromCents(80000),  // R$ 800,00
        blockedMccs: ['7995'],
        isActive: true
    );

    // Tentativa de compra de R$ 129,90 no MCC 5812 (Restaurante)
    $card->validatePurchase(
        amount: Money::fromCents(12990),
        mcc: '5812'
    );

    expect(true)->toBeTrue(); // Se não lançou exceção, passou na validação
});

// Testa se recusa a compra quando o cartão está bloqueado pela empresa
test('it throws exception when card is blocked', function () {
    $card = new Card(
        id: 'tok_carla',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(100000),
        maxPerPurchase: Money::fromCents(50000),
        blockedMccs: [],
        isActive: false // Bloqueado
    );

    expect(fn () => $card->validatePurchase(Money::fromCents(5000), '5812'))
        ->toThrow(CardIsBlockedException::class, 'Card is blocked.');
});

// Testa se recusa a compra quando o MCC do estabelecimento está na lista de bloqueados
test('it throws exception when mcc is blocked for the card', function () {
    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000),
        maxPerPurchase: Money::fromCents(80000),
        blockedMccs: ['7995'], // Jogos/Apostas bloqueado
        isActive: true
    );

    expect(fn () => $card->validatePurchase(Money::fromCents(10000), '7995'))
        ->toThrow(MccBlockedException::class, 'MCC is blocked for this card.');
});

// Testa se recusa a compra quando o valor excede o teto máximo permitido por compra
test('it throws exception when amount exceeds max per purchase ceiling', function () {
    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000),
        maxPerPurchase: Money::fromCents(80000), // Teto de R$ 800,00
        blockedMccs: [],
        isActive: true
    );

    // Tentativa de compra de R$ 850,00 (acima do teto de 800)
    expect(fn () => $card->validatePurchase(Money::fromCents(85000), '5812'))
        ->toThrow(AmountExceedsPurchaseLimitException::class, 'Amount exceeds maximum per purchase limit.');
});
