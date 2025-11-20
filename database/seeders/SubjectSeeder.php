<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::factory()->create([
            'name' =>'برمجة 1',
            'tagname'=>'برمجة 1',
            'subject_nature'=>'automation',
            'season_id' => 1
        ]);
        Subject::factory()->create([
            'name' =>'برمجة 2',
            'tagname'=>'برمجة 2',
            'subject_nature'=>'writen',
            'season_id' => 1
        ]);
        Subject::factory()->create([
            'name' =>'برمجة 3',
            'tagname'=>'برمجة 3',
            'subject_nature'=>'writen',
            'season_id' => 2
        ]);
        Subject::factory()->count(50)->create();

    }
}
