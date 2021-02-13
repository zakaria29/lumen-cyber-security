<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("files", function(Blueprint $table){
            $table->string("file_id", 30)->primary();
            $table->string("file_name");
            $table->string("question_id", 30)->index();
            $table->timestamps();

            $table->foreign("question_id")->references("question_id")->on("questions")
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
        Schema::drop("files");
    }
}
