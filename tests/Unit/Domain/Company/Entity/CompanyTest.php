<?php

declare(strict_types=1);

use Domain\Company\Entity\Company;
use Domain\Company\Exceptions\InsufficientCompanyBalanceException;
use Domain\Company\Exceptions\InvalidCompanyAttributeException;
use Domain\Shared\ValueObjects\Money;

// Testa se a empresa pode ser instanciada corretamente com o saldo inicial (ex: R$ 10.000,00 da Acme)
test('it can instantiate a company with initial balance', function () {
    $initialBalance = Money::fromCents(1000000); // R$ 10.000,00 em centavos

    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: $initialBalance
    );

    expect($company->id())->toBe('comp_acme')
        ->and($company->name())->toBe('Acme Corp')
        ->and($company->balance()->toCents())->toBe(1000000);
});

// Testa se a empresa aceita novos depósitos (créditos) aumentando seu saldo
test('it can credit amount to company balance', function () {
    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: Money::fromCents(1000000)
    );

    $company->credit(Money::fromCents(500000)); // Adiciona R$ 5.000,00

    expect($company->balance()->toCents())->toBe(1500000);
});

// Testa se a empresa debita saldo corretamente quando há fundos suficientes
test('it can debit amount from company balance when sufficient funds exist', function () {
    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: Money::fromCents(1000000)
    );

    $company->debit(Money::fromCents(12990)); // Debita R$ 129,90

    expect($company->balance()->toCents())->toBe(987010);
});

// Testa se lança exceção ao tentar debitar um valor superior ao saldo disponível da empresa
test('it throws exception when debiting more than company available balance', function () {
    $company = new Company(
        id: 'comp_acme',
        name: 'Acme Corp',
        balance: Money::fromCents(5000) // R$ 50,00
    );

    expect(fn () => $company->debit(Money::fromCents(10000))) // Tenta debitar R$ 100,00
        ->toThrow(InsufficientCompanyBalanceException::class, 'Insufficient company balance.');
});

// Testa se lança exceção ao tentar criar uma empresa com ID vazio
test('it throws exception when company id is empty', function () {
    expect(fn () => new Company(id: '', name: 'Acme Corp', balance: Money::fromCents(1000000)))
        ->toThrow(InvalidCompanyAttributeException::class, 'Company ID cannot be empty.');
});

// Testa se lança exceção ao tentar criar uma empresa com ID contendo apenas espaços em branco
test('it throws exception when company id is blank', function () {
    expect(fn () => new Company(id: '   ', name: 'Acme Corp', balance: Money::fromCents(1000000)))
        ->toThrow(InvalidCompanyAttributeException::class, 'Company ID cannot be empty.');
});

// Testa se lança exceção ao tentar criar uma empresa com nome vazio
test('it throws exception when company name is empty', function () {
    expect(fn () => new Company(id: 'comp_acme', name: '', balance: Money::fromCents(1000000)))
        ->toThrow(InvalidCompanyAttributeException::class, 'Company name cannot be empty.');
});

// Testa se lança exceção ao tentar criar uma empresa com nome contendo apenas espaços em branco
test('it throws exception when company name is blank', function () {
    expect(fn () => new Company(id: 'comp_acme', name: '   ', balance: Money::fromCents(1000000)))
        ->toThrow(InvalidCompanyAttributeException::class, 'Company name cannot be empty.');
});

// Testa se lança exceção ao tentar criar uma empresa com saldo inicial negativo
test('it throws exception when initial balance is negative', function () {
    expect(fn () => new Company(id: 'comp_acme', name: 'Acme Corp', balance: Money::fromCents(-100)))
        ->toThrow(InvalidCompanyAttributeException::class, 'Company balance cannot be negative.');
});
