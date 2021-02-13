<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("exam_category", function(Blueprint $table){
            $table->string("exam_id")->index();
            $table->string("category_id")->index();
            $table->timestamps();

            $table->foreign("exam_id")->references("exam_id")->on("exams")
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
        Schema::drop("exam_category");
    }
}
