<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'glpi_tickets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'solvedate',
        'entities_id',
        'date_creation'
    ];

    public function vnGroup(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VnGroup::class, 'items_id', 'id');
    }

    public function entity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entities_id', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'itilcategories_id');
    }

    protected function responseTimeHours(): Attribute
    {
        return Attribute::make(
            get: function () {
                $timeString = $this->vnGroup?->responseTime?->name;
                $hours = 0;

                if ($timeString && str_contains($timeString, ':')) {
                    [$h, $m] = explode(':', $timeString);
                    $hours = (int)$h + ((int)$m / 60);
                }

                return round($hours, 2);
            }
        );
    }
}
