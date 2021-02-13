<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

use App\Question;
use App\File;


class QuestionController extends Controller
{

    public function get($limit = null, $offset = null)
    {
        $question = Question::with(["category","files"]);
        if ($limit == null && $offset == null) {
            return response($question->get());
        } else {
            return response($question->take($limit)->skip($offset)->get());
        }
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = Question::with(["category","files"])
        ->where(function($q){
            $q->whereHas("category", function($que){
                $que->where("category_name","like","%$this->find%");
            })
            ->orWhereHas("files", function($que){
                $que->where("file_name","like","%$this->find%");
            })
            ->orWhere("answer_key","like","%$this->find%")
            ->orWhere("question","like","%$this->find%");
        });
        
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
                Question::create([
                    "question_id" => "QID".time(),
                    "question" => $request->question,
                    "category_id" => $request->category_id,
                    "point" => $request->point,
                    "answer_key" => $request->answer_key,
                    "status" => $request->status
                ]);

                # upload file
                // if ($request->has("files")) {
                //     $count = count($request->file("files"));
                //     for ($i=0; $i < $count ; $i++) { 
                //         $file = $request->file("files")[$i];
                //         $fileName = time()."-".$file->getClientOriginalName();
                //         $file->move(storage_path('files'), $fileName);

                //         File::create([
                //             "file_id" => "FL".time(),
                //             "file_name" => $fileName,
                //             "question_id" => $request->question_id
                //         ]);
                //     }
                // }

            } else if ($action == "update") {
                Question::where("question_id", $request->question_id)->update([
                    "question" => $request->question,
                    "category_id" => $request->category_id,
                    "point" => $request->point,
                    "answer_key" => $request->answer_key,
                    "status" => $request->status
                ]);
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($question_id)
    {
        try {
            $files = File::where("question_id", $question_id)->get();
            foreach($files as $file){
                $path = storage_path("files/".$file->file_name);
                if(file_exists($path)){
                    unlink($path);
                }
            }
            Question::where("question_id",$question_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function upload(Request $request)
    {
        try {
            $file = $request->file;
            $fileName = "FL".time().".".$file->extension();
            $request->file('file')->move(storage_path('files'), $fileName);
            return response(["status" => true, "message" => "Data has been uploaded"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
}
