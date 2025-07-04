<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $table = 'glpi_entities';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}
