<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Category;


class CategoryController extends Controller
{

    public function get($limit = null, $offset = null)
    {
        $categories = Category::with(["questions","questions.files"]);
        if ($limit == null && $offset == null) {
            return response($categories->get());
        } else {
            return response($categories->take($limit)->skip($offset)->get());
        }
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = Category::with(["questions","questions.files"])
        ->where(function($q){
            $q->whereHas("questions", function($que){
                $que->where("question","like","%$this->find%");
            })
            ->orWhereHas("questions.files", function($que){
                $que->where("file_name","like","%$this->find%");
            })
            ->orWhere("category_name","like","%$this->find%");
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
                Category::create([
                    "category_id" => "CID".time(),
                    "category_name" => $request->category_name,
                    "status" => $request->status
                ]);
            } else if ($action == "update") {
                Category::where("category_id", $request->category_id)->update([
                    "category_name" => $request->category_name,
                    "status" => $request->status
                ]);
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($category_id)
    {
        try {
            Category::where("category_id",$category_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
