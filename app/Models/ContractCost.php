<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractCost extends Model
{

    protected $table = 'glpi_contractcosts';
    protected $fillable = [
        'entities_id',
        'begin_date',
        'end_date',
        'cost',
    ];

    protected $casts = [
        'begin_date' => 'date',
        'end_date' => 'date',
    ];

    public function contract(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contracts_id', 'id');
    }
    public function contractName(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contract::class, 'entities_id', 'entities_id');
    }
}
