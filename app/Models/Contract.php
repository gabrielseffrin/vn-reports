<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'glpi_contracts';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'entities_id'
    ];

    public function costs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContractCost::class, 'entities_id', 'entities_id');
    }
}
