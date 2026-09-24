<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class CardModel extends Model
{
    public $incrementing = false;

    protected $table = 'cards';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'monthly_limit',
        'max_per_purchase',
        'blocked_mccs',
        'is_active',
    ];

    protected $casts = [
        'blocked_mccs' => 'array',
        'is_active' => 'boolean',
    ];
}
