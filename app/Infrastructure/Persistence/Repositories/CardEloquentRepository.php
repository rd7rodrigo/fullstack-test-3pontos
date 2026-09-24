<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Domain\Card\Entity\Card;
use Domain\Card\Repositories\CardRepositoryInterface;
use Infrastructure\Persistence\Eloquent\CardModel;
use Infrastructure\Persistence\Mappers\CardMapper;

final class CardEloquentRepository implements CardRepositoryInterface
{
    public function findByToken(string $token): ?Card
    {
        $model = CardModel::find($token);

        if (! $model) {
            return null;
        }

        return CardMapper::toDomain($model);
    }

    public function save(Card $card): void
    {
        $model = CardModel::find($card->id()) ?? new CardModel();

        CardMapper::toModel($card, $model);
        $model->save();
    }
}
