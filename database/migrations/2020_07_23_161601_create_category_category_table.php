<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('categories');
            $table->foreignId('child_id')->constrained('categories');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_category', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
