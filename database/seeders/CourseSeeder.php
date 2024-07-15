<?php

namespace Database\Seeders;

//use App\Models\LMS\Course;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // protected $courses = [
        //     'Introduction to islamic Studies',
        //     'The Art of Prayers',
        //     'Fasting in Perspective',
        //     'Growing your Spirit Being',
        //     'The Tenets of a wholesome life'
        // ];

        DB::table('courses')->insert([
            'reference' => uniqid('CS_', true),
            'name' => 'Introduction to islamic Studies',
            'slug' => '1',
            'description' => 'Nesciunt sapiente in omnis quam ut qui culpa. Aut quae error voluptatibus rerum atque. Itaque neque tenetur ex beatae dolores. Repellat inventore quod laboriosam cumque. Debitis animi quo cumque aut voluptas. Nemo est cupiditate ad minus impedit vero. Consequatur voluptatum velit omnis beatae. Ipsum dolores et odit maiores. Quam quia et cum sit aut laborum. Aut autem architecto quibusdam incidunt aut.',
            'price' => '1960',
        ]);
        DB::table('courses')->insert([
            'reference' => uniqid('CS_', true),
            'name' => 'The Art of Prayers',
            'slug' => '2',
            'description' => 'Nesciunt sapiente in omnis quam ut qui culpa. Aut quae error voluptatibus rerum atque. Itaque neque tenetur ex beatae dolores. Repellat inventore quod laboriosam cumque. Debitis animi quo cumque aut voluptas. Nemo est cupiditate ad minus impedit vero. Consequatur voluptatum velit omnis beatae. Ipsum dolores et odit maiores. Quam quia et cum sit aut laborum. Aut autem architecto quibusdam incidunt aut.',
            'price' => '1500',
        ]);
        DB::table('courses')->insert([
            'reference' => uniqid('CS_', true),
            'name' => 'Fasting in Perspective',
            'slug' => '3',
            'description' => 'Nesciunt sapiente in omnis quam ut qui culpa. Aut quae error voluptatibus rerum atque. Itaque neque tenetur ex beatae dolores. Repellat inventore quod laboriosam cumque. Debitis animi quo cumque aut voluptas. Nemo est cupiditate ad minus impedit vero. Consequatur voluptatum velit omnis beatae. Ipsum dolores et odit maiores. Quam quia et cum sit aut laborum. Aut autem architecto quibusdam incidunt aut.',
            'price' => '2000',
        ]);
        DB::table('courses')->insert([
            'reference' => uniqid('CS_', true),
            'name' => 'Growing your Spirit Being',
            'slug' => '4',
            'description' => 'Nesciunt sapiente in omnis quam ut qui culpa. Aut quae error voluptatibus rerum atque. Itaque neque tenetur ex beatae dolores. Repellat inventore quod laboriosam cumque. Debitis animi quo cumque aut voluptas. Nemo est cupiditate ad minus impedit vero. Consequatur voluptatum velit omnis beatae. Ipsum dolores et odit maiores. Quam quia et cum sit aut laborum. Aut autem architecto quibusdam incidunt aut.',
            'price' => '1700',
        ]);
        DB::table('courses')->insert([
            'reference' => uniqid('CS_', true),
            'name' => 'The Tenets of a wholesome life',
            'slug' => '5',
            'description' => 'Nesciunt sapiente in omnis quam ut qui culpa. Aut quae error voluptatibus rerum atque. Itaque neque tenetur ex beatae dolores. Repellat inventore quod laboriosam cumque. Debitis animi quo cumque aut voluptas. Nemo est cupiditate ad minus impedit vero. Consequatur voluptatum velit omnis beatae. Ipsum dolores et odit maiores. Quam quia et cum sit aut laborum. Aut autem architecto quibusdam incidunt aut.',
            'price' => '1800',
        ]);

        // Course::factory(5)
        //     ->hasSections(mt_rand(3, 10))
        //     ->create();
    }
}
