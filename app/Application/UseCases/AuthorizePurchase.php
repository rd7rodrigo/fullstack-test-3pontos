<?php

declare(strict_types=1);

namespace Application\UseCases;

use Domain\Card\Repositories\CardRepositoryInterface;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Ledger\Repositories\TransactionRepositoryInterface;
use Domain\Shared\ValueObjects\Money;

final class AuthorizePurchase
{
    public function __construct(
        private readonly CardRepositoryInterface $cardRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(string $cardToken, int $amountCents, string $mcc, string $merchantName): array
    {
        // 1. Buscar o cartão pelo token
        $card = $this->cardRepository->findByToken($cardToken);

        if (! $card) {
            // Lançar exceção de cartão não encontrado
        }

        // 2. Validar regras de negócio do cartão (bloqueio, teto, MCC)
        $amount = Money::fromCents($amountCents);
        $card->validatePurchase($amount, $mcc);

        // 3. O próximo passo será validar saldo/limite restante via Ledger e aprovar/recusar

        return [
            'status' => 'APPROVED',
            'reason' => null,
        ];
    }
}
