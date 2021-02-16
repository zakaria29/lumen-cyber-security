<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DoExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("do_exam", function(Blueprint $table){
            $table->string("do_exam_id", 30)->primary();
            $table->string("exam_id", 30)->index();
            $table->string("team_id", 30)->index();
            $table->dateTime("start_time");
            $table->boolean("status");
            $table->timestamps();

            $table->foreign("exam_id")->references("exam_id")->on("exams")
            ->onUpdate("cascade")->onDelete("cascade");

            $table->foreign("team_id")->references("team_id")->on("teams")
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
        Schema::drop("do_exam");
    }
}
