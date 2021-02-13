<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Exam;
use App\ExamCategory;


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
                    "token" => $this->generateToken(5)
                ]);
            } else if ($action == "update") {
                Exam::where("exam_id", $request->exam_id)->update([
                    "exam_name" => $request->exam_name,
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
                    "exam_id" => $exam_id, "category_id" => $ec->category_id
                ]);
            }
            return response(["status" => true, "message" => "Data has been deleted"]);
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
}
