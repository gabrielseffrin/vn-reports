<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnGroup extends Model
{
    use HasFactory;

    protected $table = 'glpi_plugin_fields_ticketgrupovns';
    public $timestamps = false;

    protected $fillable = [
        'plugin_fields_tempodeatendimentofielddropdowns_id',
        'items_id'
    ];

    public function responseTime()
    {
        return $this->belongsTo(ResponseTime::class, 'plugin_fields_tempodeatendimentofielddropdowns_id');
    }
}
