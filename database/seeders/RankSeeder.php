<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            [
                'name' => 'Member',
                'total_spent' => 0,
                'ticket_percentage' => 5,
                'combo_percentage' => 3,
                'is_default' => 1,
            ],
            [
                'name' => 'Gold',
                'total_spent' => 1000000,
                'ticket_percentage' => 7,
                'combo_percentage' => 5,
                'is_default' => 0,
            ],
            [
                'name' => 'Platinum',
                'total_spent' => 3000000,
                'ticket_percentage' => 10,
                'combo_percentage' => 7,
                'is_default' => 0,
            ],
            [
                'name' => 'Diamond',
                'total_spent' => 5000000,
                'ticket_percentage' => 15,
                'combo_percentage' => 10,
                'is_default' => 0,
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}
