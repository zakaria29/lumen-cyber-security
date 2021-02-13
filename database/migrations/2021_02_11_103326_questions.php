<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Questions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("questions", function(Blueprint $table){
            $table->string("question_id", 30)->primary();
            $table->text("question");
            $table->double("point");
            $table->string("answer_key");
            $table->string("category_id", 30)->index();
            $table->boolean("status");
            $table->timestamps();

            $table->foreign("category_id")->references("category_id")->on("categories")
            ->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("questions");
    }
}
