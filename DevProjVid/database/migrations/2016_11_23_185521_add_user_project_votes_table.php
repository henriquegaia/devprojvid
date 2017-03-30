<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserProjectVotesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_project_votes', function(Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('project_id');

            $table->unsignedInteger('user_id');

            $table->boolean('voted_up');

            $table->boolean('voted_down');

            //FK

            $table->foreign('project_id')
                    ->references('id')->on('projects')
                    ->onDelete('cascade');

            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
