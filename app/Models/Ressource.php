<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = [
        'section',
        'titre',
        'fichier_path',
        'fichier_nom_original',
        'lien_externe',
        'ordre',
    ];

    public static function sections(): array
    {
        return [
            'reglementation'   => 'Réglementation',
            'pedagogie'        => 'Pédagogie',
            'technique'        => 'Technique',
            'securite'         => 'Sécurité',
            'environnement'    => 'Environnement',
            'administratif'    => 'Administratif',
        ];
    }

    public function isFile(): bool
    {
        return ! empty($this->fichier_path);
    }

    public function isLink(): bool
    {
        return ! empty($this->lien_externe);
    }

    public function icon(): string
    {
        if ($this->isLink()) {
            return 'link';
        }

        $ext = strtolower(pathinfo($this->fichier_nom_original ?? '', PATHINFO_EXTENSION));

        return match($ext) {
            'pdf'             => 'pdf',
            'doc', 'docx'     => 'word',
            'xls', 'xlsx'     => 'excel',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
            default           => 'file',
        };
    }
}
