<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $languages = [
            'c',
            'c++',
            'c#',
            'php',
            'javascript',
            'java',
            'perl'
        ];
        $rndKeyLang = array_rand($languages);

        DB::table('projects')->insert([
            'name' => 'project_' . time(),
            'created_by' => 1, //rand(5, 8),
            'language' => $languages[$rndKeyLang],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }

}
