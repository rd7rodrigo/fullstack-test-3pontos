<?php

declare(strict_types=1);

use Domain\Card\Entity\Card;
use Domain\Company\Entity\Company;
use Domain\Shared\ValueObjects\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Infrastructure\Persistence\Repositories\CardEloquentRepository;
use Infrastructure\Persistence\Repositories\CompanyEloquentRepository;

uses(RefreshDatabase::class);

/**
 * Garante que o cartão é persistido corretamente no banco e pode ser recuperado pelo token.
 */
test('[CardEloquentRepository] it can save and find a card by token', function () {
    $companyRepository = new CompanyEloquentRepository();
    $company = new Company('comp_acme', 'Acme Corp', Money::fromCents(1000000));
    $companyRepository->save($company);

    $cardRepository = new CardEloquentRepository();

    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000),
        maxPerPurchase: Money::fromCents(50000),
        blockedMccs: ['7995', '5967'],
        isActive: true
    );

    $cardRepository->save($card);

    $foundCard = $cardRepository->findByToken('tok_ana');

    expect($foundCard)->not->toBeNull()
        ->and($foundCard->id())->toBe('tok_ana')
        ->and($foundCard->companyId())->toBe('comp_acme')
        ->and($foundCard->monthlyLimit()->toCents())->toBe(200000)
        ->and($foundCard->maxPerPurchase()->toCents())->toBe(50000)
        ->and($foundCard->blockedMccs())->toBe(['7995', '5967'])
        ->and($foundCard->isActive())->toBeTrue();
});

/**
 * Garante que o repositório retorna null ao tentar buscar um cartão com token inexistente.
 */
test('[CardEloquentRepository] it returns null when card token is not found', function () {
    $cardRepository = new CardEloquentRepository();

    $foundCard = $cardRepository->findByToken('invalid_token');

    expect($foundCard)->toBeNull();
});

/**
 * Garante que o repositório atualiza corretamente o status e os limites de um cartão existente.
 */
test('[CardEloquentRepository] it can update an existing card status and limits', function () {
    $companyRepository = new CompanyEloquentRepository();
    $company = new Company('comp_acme', 'Acme Corp', Money::fromCents(1000000));
    $companyRepository->save($company);

    $cardRepository = new CardEloquentRepository();

    $card = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(200000),
        maxPerPurchase: Money::fromCents(50000),
        blockedMccs: [],
        isActive: true
    );

    $cardRepository->save($card);

    // Bloqueando o cartão e alterando limites
    $card->block();
    $updatedCard = new Card(
        id: 'tok_ana',
        companyId: 'comp_acme',
        monthlyLimit: Money::fromCents(300000),
        maxPerPurchase: Money::fromCents(80000),
        blockedMccs: ['7995'],
        isActive: $card->isActive()
    );

    $cardRepository->save($updatedCard);

    $foundCard = $cardRepository->findByToken('tok_ana');

    expect($foundCard->isActive())->toBeFalse()
        ->and($foundCard->monthlyLimit()->toCents())->toBe(300000)
        ->and($foundCard->maxPerPurchase()->toCents())->toBe(80000)
        ->and($foundCard->blockedMccs())->toBe(['7995']);
});
