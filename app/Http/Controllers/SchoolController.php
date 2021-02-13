<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\School;


class SchoolController extends Controller
{

    public function get($limit = null, $offset = null)
    {
        $school = School::with(["teams","teams.members"]);
        if ($limit == null && $offset == null) {
            return response($school->get());
        } else {
            return response($school->take($limit)->skip($offset)->get());
        }
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = School::with(["teams","teams.members"])->where("school_name","like","%$this->find%")
        ->orWhere("school_address","like","%$this->find%");
        
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
                School::create([
                    "school_id" => "SID".time(),
                    "school_name" => $request->school_name,
                    "school_address" => $request->school_address
                ]);
            } else if ($action == "update") {
                School::where("school_id", $request->school_id)->update([
                    "school_name" => $request->school_name,
                    "school_address" => $request->school_address
                ]);
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($school_id)
    {
        try {
            School::where("school_id",$school_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
