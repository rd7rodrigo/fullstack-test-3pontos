<?php

declare(strict_types=1);

namespace Domain\Company\Repositories;

use Domain\Company\Entity\Company;

interface CompanyRepositoryInterface
{
    public function findById(string $id): ?Company;

    public function save(Company $company): void;
}
