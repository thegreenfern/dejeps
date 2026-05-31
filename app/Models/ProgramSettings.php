<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSettings extends Model
{
    protected $fillable = [
        'uc1_submission_deadline',
        'uc1_jury_date',
        'uc2_submission_deadline',
        'uc2_jury_date',
        'epmsp_date',
        'dc_name',
        'dc_address',
        'dc_type',
        'dc_director',
        'dc_email',
        'dc_phone',
        'dc_description',
        'dc_notes',
    ];

    protected $casts = [
        'uc1_submission_deadline' => 'date',
        'uc1_jury_date'           => 'date',
        'uc2_submission_deadline' => 'date',
        'uc2_jury_date'           => 'date',
        'epmsp_date'              => 'date',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate([]);
    }
}
