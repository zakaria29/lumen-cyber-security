<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(["prefix" => "api/v1","middleware" => "admin"], function() use ($router){
    # admin
    $router->get("/admin[/{limit}/{offset}]", "AdminController@get");
    $router->post("/admin[/{limit}/{offset}]", "AdminController@find");
    $router->post("/admin-save", "AdminController@save");
    $router->delete("/admin/drop/{admin_id}", "AdminController@drop");

    # judge
    $router->get("/judge[/{limit}/{offset}]", "JudgeController@get");
    $router->post("/judge[/{limit}/{offset}]", "JudgeController@find");
    $router->post("/judge-save", "JudgeController@save");
    $router->delete("/judge/drop/{judge_id}", "JudgeController@drop");

    # member
    $router->get("/member[/{limit}/{offset}]", "MemberController@get");
    $router->post("/member[/{limit}/{offset}]", "MemberController@find");
    $router->post("/member-save", "MemberController@save");
    $router->delete("/member/drop/{member_id}", "MemberController@drop");

    # team
    $router->get("/team[/{limit}/{offset}]", "TeamController@get");
    $router->post("/team[/{limit}/{offset}]", "TeamController@find");
    $router->post("/team-save", "TeamController@save");
    $router->delete("/team/drop/{team_id}", "TeamController@drop");

    # school
    $router->get("/school[/{limit}/{offset}]", "SchoolController@get");
    $router->post("/school[/{limit}/{offset}]", "SchoolController@find");
    $router->post("/school-save", "SchoolController@save");
    $router->delete("/school/drop/{school_id}", "SchoolController@drop");

    # category
    $router->get("/category[/{limit}/{offset}]", "CategoryController@get");
    $router->post("/category[/{limit}/{offset}]", "CategoryController@find");
    $router->post("/category-save", "CategoryController@save");
    $router->delete("/category/drop/{category_id}", "CategoryController@drop");
    $router->get("/active-category", "CategoryController@getActiveCategory");

    # question
    $router->get("/question[/{limit}/{offset}]", "QuestionController@get");
    $router->post("/question[/{limit}/{offset}]", "QuestionController@find");
    $router->post("/question-save", "QuestionController@save");
    $router->delete("/question/drop/{question_id}", "QuestionController@drop");

    # file
    $router->post("/file/save", "FileController@save");
    $router->delete("/file/drop/{file_id}", "FileController@drop");

    # exam
    $router->get("/exam[/{limit}/{offset}]", "ExamController@get");
    $router->post("/exam[/{limit}/{offset}]", "ExamController@find");
    $router->post("/exam-save", "ExamController@save");
    $router->delete("/exam/drop/{exam_id}", "ExamController@drop");
    $router->get("/refresh-token/{exam_id}", "ExamController@refreshToken");
    $router->post("/exam-category/save", "ExamController@matchCategory");
});

$router->group(["prefix" => "api/v2","middleware" => "member"], function() use ($router){
    #exam
    $router->get("/exam-list", "ExamController@getForTeam");
    $router->post("/sendToken", "ExamController@sendToken");
    $router->post("/get-question", "ExamController@getQuestion");
    $router->post("/get-result", "ExamController@getResult");
    $router->post("/submit-answer", "ExamController@setAnswer");
});


# authentication
$router->group(["prefix" => "api/v1"], function() use ($router){
    $router->post("/admin/auth", "AdminController@authenticate");
    $router->post("/judge/auth", "JudgeController@authenticate");
});

$router->group(["prefix" => "api/v2"], function() use ($router){
    $router->post("/member/auth", "MemberController@authenticate");
});


# test upload
$router->post("/api/v1/upload", "QuestionController@upload");
