<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Member;


class MemberController extends Controller
{

    public function authenticate(Request $request)
    {
        $username = $request->username;
        $password = sha1($request->password).md5($request->password);
        
        $query = Member::with(["team","team.school"])->where("username", $username);
        if ($query->count() > 0) {
            $member = $query->first();
            $checkPassword = $member->password == $password;
            if ($checkPassword) {
                $token = sha1($member->member_id."-".rand(1,10000));
                Member::where("member_id", $member->member_id)->update(["token" => $token]);
                return response([
                    "logged" => true,
                    "message" => "Log In Success",
                    "data" => $member,
                    "token" => $token
                ]);
            } else {
                return response([
                    "logged" => false,
                    "message" => "Incorrect Password"
                ]);
            }
        }
        else{
            return response([
                "logged" => false,
                "message" => "Invalid Username"
            ]);
        }
    }

    public function get($limit = null, $offset = null)
    {
        $member = Member::with(["team","team.school"]);
        if ($limit == null && $offset == null) {
            return response($member->get());
        } else {
            return response($member->take($limit)->skip($offset)->get());
        }
    }

    public $find;
    public function find(Request $request, $limit = null, $offset = null)
    {
        $this->find = $request->find;
        $query = Member::with(["team","team.school"])
        ->where(function($q){
            $q->whereHas("team", function($que){
                $que->where("team_name","like","%$this->find%");
            })
            ->orWhereHas("team.school", function($que){
                $que->where("school_name","like","%$this->find%")
                ->orWhere("school_address","like","%$this->find%");
            })
            ->orWhere("member_name","like","%$this->find%")
            ->orWhere("email","like","%$this->find%")
            ->orWhere("username","like","%$this->find%");
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
                Member::create([
                    "member_id" => "MID".time(),
                    "member_name" => $request->member_name,
                    "username" => $request->username,
                    "team_id" => $request->team_id,
                    "email" => $request->email,
                    "password" => sha1($request->password).md5($request->password),
                    "token" => ""
                ]);
            } else if ($action == "update") {
                if ($request->has("password")){
                    Member::where("member_id", $request->member_id)->update([
                        "member_name" => $request->member_name,
                        "username" => $request->username,
                        "team_id" => $request->team_id,
                        "email" => $request->email,
                        "password" => sha1($request->password).md5($request->password)
                    ]);
                }else{
                    Member::where("member_id", $request->member_id)->update([
                        "member_name" => $request->member_name,
                        "username" => $request->username,
                        "team_id" => $request->team_id,
                        "email" => $request->email,
                    ]);
                }
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($member_id)
    {
        try {
            Member::where("member_id",$member_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
