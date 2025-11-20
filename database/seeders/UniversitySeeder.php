<?php

namespace Database\Seeders;

use App\Models\Season;
use App\Models\University;
use App\Models\Year;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        University::factory()->create([
            'collagename' => 'جامعة حلب',
        ])->each(function ($university) {
            // For each university, create 5 years
            $tempname = 'السنة الأولى';
            for ($yearNum = 1; $yearNum <= 5; $yearNum++) {
                switch ($yearNum) {
                    case 1:
                        $tempname = 'السنة الأولى';
                        break;
                    case 2:
                        $tempname = 'السنة الثانية';
                        break;
                    case 3:
                        $tempname = 'السنة الثالثة';
                        break;
                    case 4:
                        $tempname = 'السنة الرابعة';
                        break;
                    case 5:
                        $tempname = 'السنة الخامسة';
                        break;

                    default:
                        # code...
                        break;
                }
                $year = Year::factory()->create([
                    'university_id' => $university->id,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);

                // For each year, create 2 seasons
                for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
                    Season::factory()->create([
                        'year_id' => $year->id,
                        'season_num' => $seasonNum,
                        'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
                    ]);
                }
            }
        });
        University::factory()->create([
            'collagename' => 'جامعة حمص',
        ])->each(function ($university) {
            // For each university, create 5 years
            $tempname = 'السنة الأولى';
            for ($yearNum = 1; $yearNum <= 5; $yearNum++) {
                switch ($yearNum) {
                    case 1:
                        $tempname = 'السنة الأولى';
                        break;
                    case 2:
                        $tempname = 'السنة الثانية';
                        break;
                    case 3:
                        $tempname = 'السنة الثالثة';
                        break;
                    case 4:
                        $tempname = 'السنة الرابعة';
                        break;
                    case 5:
                        $tempname = 'السنة الخامسة';
                        break;

                    default:
                        # code...
                        break;
                }
                $year = Year::factory()->create([
                    'university_id' => $university->id,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);

                // For each year, create 2 seasons
                for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
                    Season::factory()->create([
                        'year_id' => $year->id,
                        'season_num' => $seasonNum,
                        'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
                    ]);
                }
            }
        });
        University::factory()->create([
            'collagename' => 'جامعة دمشق',
        ])->each(function ($university) {
            // For each university, create 5 years
            $tempname = 'السنة الأولى';
            for ($yearNum = 1; $yearNum <= 5; $yearNum++) {
                switch ($yearNum) {
                    case 1:
                        $tempname = 'السنة الأولى';
                        break;
                    case 2:
                        $tempname = 'السنة الثانية';
                        break;
                    case 3:
                        $tempname = 'السنة الثالثة';
                        break;
                    case 4:
                        $tempname = 'السنة الرابعة';
                        break;
                    case 5:
                        $tempname = 'السنة الخامسة';
                        break;

                    default:
                        # code...
                        break;
                }
                $year = Year::factory()->create([
                    'university_id' => $university->id,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);

                // For each year, create 2 seasons
                for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
                    Season::factory()->create([
                        'year_id' => $year->id,
                        'season_num' => $seasonNum,
                        'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
                    ]);
                }
            }
        });
    }
}
