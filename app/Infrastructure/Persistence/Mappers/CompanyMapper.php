<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Mappers;

use Domain\Company\Entity\Company;
use Domain\Shared\ValueObjects\Money;
use Infrastructure\Persistence\Eloquent\CompanyModel;

final class CompanyMapper
{
    public static function toDomain(CompanyModel $model): Company
    {
        return new Company(
            id: $model->id,
            name: $model->name,
            balance: Money::fromCents($model->balance)
        );
    }

    public static function toModel(Company $company, CompanyModel $model): void
    {
        $model->id = $company->id();
        $model->name = $company->name();
        $model->balance = $company->balance()->toCents();
    }
}
