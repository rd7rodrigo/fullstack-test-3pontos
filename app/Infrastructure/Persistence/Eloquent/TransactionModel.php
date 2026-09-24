<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class TransactionModel extends Model
{
    public $incrementing = false;

    protected $table = 'transactions';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'card_id',
        'company_id',
        'amount',
        'type',
        'reference_id',
    ];
}
