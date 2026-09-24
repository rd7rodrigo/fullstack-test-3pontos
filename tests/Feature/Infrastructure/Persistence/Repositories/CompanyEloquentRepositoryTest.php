<?php

declare(strict_types=1);

use Domain\Company\Entity\Company;
use Domain\Shared\ValueObjects\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Infrastructure\Persistence\Repositories\CompanyEloquentRepository;

uses(RefreshDatabase::class);

/**
 * Garante que a empresa é persistida corretamente no banco e pode ser recuperada pelo ID.
 */
test('[CompanyEloquentRepository] it can save and find a company by id', function () {
    $repository = new CompanyEloquentRepository();

    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: Money::fromCents(1000000)
    );

    $repository->save($company);

    $foundCompany = $repository->findById('comp_acme');

    expect($foundCompany)->not->toBeNull()
        ->and($foundCompany->id())->toBe('comp_acme')
        ->and($foundCompany->name())->toBe('Acme Corp')
        ->and($foundCompany->balance()->toCents())->toBe(1000000);
});

/**
 * Garante que o repositório retorna null ao tentar buscar uma empresa com ID inexistente.
 */
test('[CompanyEloquentRepository] it returns null when company is not found', function () {
    $repository = new CompanyEloquentRepository();

    $foundCompany = $repository->findById('non_existent');

    expect($foundCompany)->toBeNull();
});

/**
 * Garante que o repositório atualiza corretamente os dados de uma empresa existente ao salvá-la novamente.
 */
test('[CompanyEloquentRepository] it can update an existing company', function () {
    $repository = new CompanyEloquentRepository();

    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: Money::fromCents(1000000)
    );

    $repository->save($company);

    // Modificando o saldo e o nome para simular uma atualização
    $updatedCompany = new Company(
        id: 'comp_acme',
        name: 'Acme Updated',
        balance: Money::fromCents(1500000)
    );

    $repository->save($updatedCompany);

    $foundCompany = $repository->findById('comp_acme');

    expect($foundCompany->name())->toBe('Acme Updated')
        ->and($foundCompany->balance()->toCents())->toBe(1500000);
});
