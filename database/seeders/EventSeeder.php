<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        Event::create([
            'title' => 'Forum',
            'description'=>'Forum de Master',
            'start'=>'2022-03-21 10:00:00',
            'end'=>'2022-03-21 18:00:00',
        ]);
        Event::create([
            'title' => 'Kognitif',
            'description'=>'Dépot du test',
            'start'=>'2022-03-24 8:00:00',
            'end'=>'2022-03-24 20:00:00',
        ]);
        Event::create([
            'title' => 'Hopital',
            'description'=>'Controle chez le medecin',
            'start'=>'2022-03-25 09:00:00',
            'end'=>'2022-03-25 10:30:00',
        ]);
        Event::create([
            'title' => 'Soutenance',
            'description'=>'Soutenance chez M Furst',
            'start'=>'2022-03-28 09:00:00',
            'end'=>'2022-03-28 10:00:00',
        ]);
    }
}