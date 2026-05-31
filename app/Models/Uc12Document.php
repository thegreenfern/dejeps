<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uc12Document extends Model
{
    protected $fillable = ['original_name', 'stored_path', 'mime_type', 'size'];

    public function formattedSize(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) return $bytes . ' o';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / (1024 * 1024), 1) . ' Mo';
    }
}
