<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultValsUers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('is_developer');
            $table->dropColumn('is_company');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->unsignedInteger('is_developer')->default(0);
            $table->unsignedInteger('is_company')->default(0);
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
