<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Domain\Company\Entity\Company;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Infrastructure\Persistence\Eloquent\CompanyModel;
use Infrastructure\Persistence\Mappers\CompanyMapper;

final class CompanyEloquentRepository implements CompanyRepositoryInterface
{
    public function findById(string $id): ?Company
    {
        $model = CompanyModel::find($id);

        if (! $model) {
            return null;
        }

        return CompanyMapper::toDomain($model);
    }

    public function save(Company $company): void
    {
        $model = CompanyModel::find($company->id()) ?? new CompanyModel();

        CompanyMapper::toModel($company, $model);
        $model->save();
    }
}
