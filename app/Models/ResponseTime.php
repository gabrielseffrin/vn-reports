<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseTime extends Model
{
    use HasFactory;

    protected $table = 'glpi_plugin_fields_tempodeatendimentofielddropdowns';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

}
