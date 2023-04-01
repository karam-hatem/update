<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('menu_id')->references('id')->on('menus');
        });

        // Schema::table('items', function (Blueprint $table) {
        //     $table->foreign('section_id')->references('id')->on('sections');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables_relations');
    }
};
