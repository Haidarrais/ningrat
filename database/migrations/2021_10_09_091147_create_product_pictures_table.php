<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::create('product_pictures', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('image');
            $table->tinyInteger('is_important');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image','255');
        });
        Schema::dropIfExists('product_pictures');
    }
}
