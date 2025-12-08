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
        ]);

        University::factory()->create([
            'collagename' => 'جامعة حمص',
        ]);
        University::factory()->create([
            'collagename' => 'جامعة دمشق',
        ]);
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
                    'university_id' => 3,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);
                $year = Year::factory()->create([
                    'university_id' => 2,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);
                $year = Year::factory()->create([
                    'university_id' => 1,
                    'number' => $yearNum,
                    'name' => $tempname,
                ]);


            }


        for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
                Season::factory()->create([
                    'university_id' => 1,
                    'number' => $seasonNum,

                    'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
                ]);
            }
        for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
            Season::factory()->create([
                'university_id' => 2,
                'number' => $seasonNum,
                'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
            ]);
        }
        for ($seasonNum = 1; $seasonNum <= 2; $seasonNum++) {
            Season::factory()->create([
                'university_id' => 3,
                'number' => $seasonNum,

                'name' => $seasonNum === 1 ? 'الفصل الأول' : 'الفصل الثاني',
            ]);
        }
    }
}
