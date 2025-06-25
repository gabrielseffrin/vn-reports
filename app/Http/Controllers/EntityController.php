<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function getEntities()
    {
        return Entity::all()
            ->map(function ($entity) {
                return [
                    'id' => $entity->id,
                    'name' => $entity->name,
                ];
            });
    }
}
