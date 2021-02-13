<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("exam_details", function(Blueprint $table){
            $table->string("do_exam_id", 30)->index();
            $table->string("member_id", 30)->index();
            $table->string("question_id", 30)->index();
            $table->string("category_id", 30)->index();
            $table->string("answer");
            $table->double("score");
            $table->timestamps();

            $table->foreign("do_exam_id")->references("do_exam_id")->on("do_exam")
            ->onUpdate("cascade")->onDelete("cascade");

            $table->foreign("member_id")->references("member_id")->on("members")
            ->onUpdate("cascade")->onDelete("cascade");

            $table->foreign("question_id")->references("question_id")->on("questions")
            ->onUpdate("cascade")->onDelete("cascade");

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
        Schema::drop("exam_details");
    }
}
