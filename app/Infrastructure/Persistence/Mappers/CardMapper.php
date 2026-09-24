<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Mappers;

use Domain\Card\Entity\Card;
use Domain\Shared\ValueObjects\Money;
use Infrastructure\Persistence\Eloquent\CardModel;

final class CardMapper
{
    public static function toDomain(CardModel $model): Card
    {
        return new Card(
            id: $model->id,
            companyId: $model->company_id,
            monthlyLimit: Money::fromCents($model->monthly_limit),
            maxPerPurchase: Money::fromCents($model->max_per_purchase),
            blockedMccs: $model->blocked_mccs ?? [],
            isActive: $model->is_active
        );
    }

    public static function toModel(Card $card, CardModel $model): void
    {
        $model->id = $card->id();
        $model->company_id = $card->companyId();
        $model->monthly_limit = $card->monthlyLimit()->toCents();
        $model->max_per_purchase = $card->maxPerPurchase()->toCents();
        $model->blocked_mccs = $card->blockedMccs();
        $model->is_active = $card->isActive();
    }
}
