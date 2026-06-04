<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePlongee extends Model
{
    protected $table = 'sites_plongee';

    protected $fillable = [
        'nom',
        'latitude',
        'longitude',
        'profondeur_max',
        'niveau_requis',
        'description',
    ];

    protected $casts = [
        'latitude'      => 'float',
        'longitude'     => 'float',
        'profondeur_max' => 'integer',
    ];

    public static function niveaux(): array
    {
        return ['N1', 'N2', 'N3', 'N4', 'PE40', 'Tous niveaux'];
    }
}
