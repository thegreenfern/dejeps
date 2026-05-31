<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'asana_gid', 'name', 'section', 'event_type',
        'start_on', 'due_on', 'completed', 'assignee_name',
        'asana_url', 'synced_at',
    ];

    protected $casts = [
        'start_on'  => 'date',
        'due_on'    => 'date',
        'completed' => 'boolean',
        'synced_at' => 'datetime',
    ];

    // -600 weight colours: all pass WCAG AA with white text at ≥ 10px bold
    public static function typeColors(): array
    {
        return [
            'Formation'             => ['hex' => '#2563eb', 'label_hex' => '#dbeafe', 'dot' => '#2563eb'],
            'Observation'           => ['hex' => '#d97706', 'label_hex' => '#fef3c7', 'dot' => '#d97706'],
            'Supervision directe'   => ['hex' => '#ea580c', 'label_hex' => '#ffedd5', 'dot' => '#ea580c'],
            'Autonomie'             => ['hex' => '#059669', 'label_hex' => '#d1fae5', 'dot' => '#059669'],
            'Supervision indirecte' => ['hex' => '#7c3aed', 'label_hex' => '#ede9fe', 'dot' => '#7c3aed'],
            'Examen'                => ['hex' => '#dc2626', 'label_hex' => '#fee2e2', 'dot' => '#dc2626'],
            ''                      => ['hex' => '#475569', 'label_hex' => '#f1f5f9', 'dot' => '#475569'],
        ];
    }
}
