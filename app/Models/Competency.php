<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Competency extends Model
{
    protected $fillable = [
        'uc', 'framework', 'category', 'code', 'label', 'sort_order', 'active',
    ];

    public function scopePositioning(Builder $query): Builder
    {
        return $query->where('framework', 'positioning')->where('active', true)->orderBy('sort_order');
    }

    public function scopeForExam(Builder $query, string $examType): Builder
    {
        return $query->where('framework', 'certification')
                     ->where('category', $examType)
                     ->where('active', true)
                     ->orderBy('sort_order');
    }
}
