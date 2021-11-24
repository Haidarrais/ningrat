<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->date('ttl')->nullable()->after('address');
            $table->integer('nowhatsapp')->nullable()->after('address');
            $table->string('facebook')->nullable()->after('address');
            $table->string('instagram')->nullable()->after('address');
            $table->string('marketplace')->nullable()->after('address');
            $table->string('mou')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            //
            $table->dropColumn('ttl');
            $table->dropColumn('nowhatsapp');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
            $table->dropColumn('marketplace');
            $table->dropColumn('mou');
        });
    }
}
