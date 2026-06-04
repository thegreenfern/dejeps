<?php

namespace Database\Seeders;

use App\Models\SitePlongee;
use Illuminate\Database\Seeder;

class SitesPlongeeSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'nom'           => 'La Revellata',
                'latitude'      => 42.5831,
                'longitude'     => 8.7275,
                'profondeur_max' => 40,
                'niveau_requis' => 'N2',
                'description'   => 'Pointe rocheuse avec tombant, riche en gorgones et faune pélagique.',
            ],
            [
                'nom'           => 'La Citadelle',
                'latitude'      => 42.5672,
                'longitude'     => 8.7583,
                'profondeur_max' => 20,
                'niveau_requis' => 'N1',
                'description'   => 'Petits fonds sous la citadelle de Calvi, idéal pour les formations.',
            ],
            [
                'nom'           => 'Épave du B-17',
                'latitude'      => 42.5720,
                'longitude'     => 8.7430,
                'profondeur_max' => 22,
                'niveau_requis' => 'N2',
                'description'   => 'Bombardier américain de la Seconde Guerre Mondiale, posé par 22 m.',
            ],
            [
                'nom'           => 'Le Sec de la Citadelle',
                'latitude'      => 42.5710,
                'longitude'     => 8.7500,
                'profondeur_max' => 30,
                'niveau_requis' => 'N2',
                'description'   => 'Sec rocheux recouvert de gorgones jaunes et rouges.',
            ],
            [
                'nom'           => 'Les Rochers de Calvi',
                'latitude'      => 42.5650,
                'longitude'     => 8.7650,
                'profondeur_max' => 15,
                'niveau_requis' => 'Tous niveaux',
                'description'   => 'Zone de rochers côtiers, excellente pour les débutants et la biologie.',
            ],
            [
                'nom'           => 'Le Tombant de la Revellata',
                'latitude'      => 42.5850,
                'longitude'     => 8.7250,
                'profondeur_max' => 60,
                'niveau_requis' => 'N3',
                'description'   => 'Tombant vertigineux avec corail, barracudas et mérous.',
            ],
            [
                'nom'           => 'La Phare',
                'latitude'      => 42.5885,
                'longitude'     => 8.7210,
                'profondeur_max' => 35,
                'niveau_requis' => 'N2',
                'description'   => 'Pointe sous le phare de la Revellata, bon courant et belle visibilité.',
            ],
            [
                'nom'           => 'Anse de Calvi',
                'latitude'      => 42.5640,
                'longitude'     => 8.7700,
                'profondeur_max' => 10,
                'niveau_requis' => 'Tous niveaux',
                'description'   => 'Baie abritée peu profonde, parfaite pour les baptêmes et découvertes.',
            ],
        ];

        foreach ($sites as $site) {
            SitePlongee::firstOrCreate(['nom' => $site['nom']], $site);
        }
    }
}
