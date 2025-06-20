<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'glpi_itilcategories';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'completename'
    ];

    protected function simplifiedName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(explode('>', $this->completename)[0])
        );
    }


}
