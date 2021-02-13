<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Team;


class TeamController extends Controller
{

    public function get($limit = null, $offset = null)
    {
        if ($limit == null && $offset == null) {
            return response(Team::with(["school"])->get());
        } else {
            return response(Team::with(["school"])->take($limit)->skip($offset)->get());
        }
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = Team::with(["school"])
        ->where(function($q){
            $q->whereHas("school", function($que){
                $que->where("school_name","like","%$this->find%");
            })
            ->orWhere("team_name","like","%$this->find%");
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
                Team::create([
                    "team_id" => "TID".time(),
                    "team_name" => $request->team_name,
                    "school_id" => $request->school_id
                ]);
            } else if ($action == "update") {
                Team::where("team_id", $request->team_id)->update([
                    "team_name" => $request->team_name,
                    "school_id" => $request->school_id
                ]);
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($team_id)
    {
        try {
            Team::where("team_id",$team_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
