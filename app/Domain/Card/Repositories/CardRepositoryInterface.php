<?php

declare(strict_types=1);

namespace Domain\Card\Repositories;

use Domain\Card\Entity\Card;

interface CardRepositoryInterface
{
    public function findByToken(string $token): ?Card;

    public function save(Card $card): void;
}
