<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObjects;

final readonly class Money
{
    public function __construct(
        private int $cents
    ) {
        // Se a regra permitir saldo zero ou positivo, podemos validar aqui,
        // mas o Ledger gerencia se o saldo pode ficar negativo ou não.
    }

    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    public function toCents(): int
    {
        return $this->cents;
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->toCents());
    }

    public function subtract(self $other): self
    {
        return new self($this->cents - $other->toCents());
    }

    public function equals(self $other): bool
    {
        return $this->cents === $other->toCents();
    }

    public function isGreaterThanOrEqual(self $other): bool
    {
        return $this->cents >= $other->toCents();
    }

    public function isZero(): bool
    {
        return $this->cents === 0;
    }
}
