<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnRewardUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_user', function(Blueprint $table) {
            $table->renameColumn('reward_id', 'point_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_user', function(Blueprint $table) {
            $table->renameColumn('point_id', 'reward_id');
        });
    }
}
