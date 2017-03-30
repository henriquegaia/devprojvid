<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewMatches extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("create view matches 
            as select projects.language as language, projects.id as ProjectId, requests.id as RequestId
            from projects
            inner join requests
            on projects.language = requests.language");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('matches');
    }

}
