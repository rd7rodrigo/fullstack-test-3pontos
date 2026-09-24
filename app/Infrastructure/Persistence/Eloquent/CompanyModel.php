<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class CompanyModel extends Model
{
    public $incrementing = false;

    protected $table = 'companies';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'balance',
    ];
}
