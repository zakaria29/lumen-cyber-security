<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Exam;
use App\ExamCategory;
use App\DoExam;
use App\ExamDetail;
use App\Question;
use DB;


class ExamController extends Controller
{

    public function get($limit = null, $offset = null)
    {
        $exam = Exam::with(["exam_category"]);
        if ($limit == null && $offset == null) {
            return response($exam->get());
        } else {
            return response($exam->take($limit)->skip($offset)->get());
        }
    }

    public function getForTeam()
    {
        $exam = Exam::select(['exam_id','exam_name','status'])
        ->with(["exam_details"])
        ->get();
        return response($exam);
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = Exam::with(["exam_category"])->where("exam_name","like","%$this->find%");
        
        if ($limit == null && $offset == null) {
            return response($query->get());
        } else {
            return response($query->take($limit)->skip($offset)->get());
        }
    }

    public function save(Request $request)
    {
        try {
            $action = $request->action;
            if ($action == "insert") {
                Exam::create([
                    "exam_id" => "EID".time(),
                    "exam_name" => $request->exam_name,
                    "status" => $request->status,
                    "token" => $this->generateToken(5)
                ]);
            } else if ($action == "update") {
                Exam::where("exam_id", $request->exam_id)->update([
                    "exam_name" => $request->exam_name,
                    "status" => $request->status,
                ]);
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function refreshToken($exam_id)
    {
        try {
            Exam::where("exam_id", $exam_id)->update([
                "token" => $this->generateToken(5)
            ]);
            return response(["status" => true, "message" => "Token has been changed"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($exam_id)
    {
        try {
            Exam::where("exam_id",$exam_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function matchCategory(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            ExamCategory::where("exam_id", $exam_id)->delete();
            $examCategory = json_decode($request->exam_category);
            foreach ($examCategory as $ec) {
                ExamCategory::create([
                    "exam_id" => $exam_id, "category_id" => $ec
                ]);
            }
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function generateToken($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function sendToken(Request $request)
    {
        try {
            $token = $request->token;
            $count = Exam::where("token",$token)->count();
            if ($count > 0) {
                $countTeam = DoExam::where("team_id", $request->team_id)->where("exam_id", $request->exam_id)
                ->count();
                if ($countTeam == 0) {
                    $do_exam_id = "DOEX".time().rand(1,1000);
                    DoExam::create([
                        "do_exam_id" => $do_exam_id,
                        "start_time" => date("Y-m-d H:i:s"),
                        "team_id" => $request->team_id,
                        "exam_id" => $request->exam_id,
                        "status" => false
                    ]);
                }else{
                    $do_exam_id = DoExam::where("team_id", $request->team_id)
                    ->where("exam_id", $request->exam_id)->first()->do_exam_id;
                }
                return response([
                    "status" => true, "message" => "Token Valid", "do_exam_id" => $do_exam_id
                ]);
            } else {
                return response(["status" => false, "message" => "Token Invalid"]);
            }
            
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function getQuestion(Request $request)
    {
        $data = ExamCategory::with(["category","category.questions" => function($query){
            $query->select("question_id","question","point","category_id")
            ->where("status",1)->orderBy("point","asc");
        },"category.questions.files"])
        ->where("exam_id", $request->exam_id)->get();
        return response($data);
    }

    public function getResult(Request $request)
    {
        $data = ExamDetail::where("do_exam_id", $request->do_exam_id)->get();
        $exam = DoExam::where("do_exam_id", $request->do_exam_id)->first();
        return response([
            "results" => $data,
            "exam" => $exam
        ]);
    }

    public function setAnswer(Request $request)
    {
        try {
            $do_exam_id = $request->do_exam_id;
            $member_id = $request->member_id;
            $question_id = $request->question_id;
            $answer = $request->answer;
            $question = Question::where("question_id", $question_id)->first();
            $category_id = $question->category_id;
            $score = ($answer == $question->answer_key) ? $question->point : 0;
            ExamDetail::where("do_exam_id", $do_exam_id)->where("question_id", $question_id)->delete();
            ExamDetail::create([
                "do_exam_id" => $do_exam_id, "member_id" => $member_id,
                "question_id" => $question_id, "category_id" => $category_id,
                "answer" => $answer, "score" => $score
            ]);
            return response(["status" => true,
            "result" => ($answer == $question->answer_key),
            "message" => "Your Answer Submited"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function finish(Request $request)
    {
        try {
            DoExam::where("do_exam_id", $request->do_exam_id)->update(["status" => 1]);
            return response(["status" => true, "message" => "Exam Finished"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function getScore($exam_id)
    {
        $data = Exam::where("exam_id", $exam_id)
        ->with(["exam_details", "exam_details.team", "exam_details.exam_details" => function($query){
            $query->select("do_exam_id","category_id",DB::raw("sum(score) as score"))
            ->groupBy(["category_id","do_exam_id"]);
        },"exam_details.exam_details.category"])
        ->get();

        return response($data);
    }

    public function resetExam(Request $request)
    {
        try {
            DoExam::where("exam_id",$request->exam_id)->where("team_id", $request->team_id)->delete();
            return response(["status" => true, "message" => "Exam Reseted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
