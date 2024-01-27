<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentUserRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_user_rating', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('rating_id')->constrained('ratings');
            $table->timestamps();
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
        Schema::table('comment_user_rating', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
