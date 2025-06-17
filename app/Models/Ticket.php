<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'glpi_tickets';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'solvedate',
        'entities_id'
    ];

    public function vnGroup()
    {
        return $this->hasOne(VnGroup::class, 'items_id', 'id');
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
