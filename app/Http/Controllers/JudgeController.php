<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Judge;


class JudgeController extends Controller
{

    public function authenticate(Request $request)
    {
        $username = $request->username;
        $password = sha1($request->password).md5($request->password);
        
        $query = Judge::where("username", $username);
        if ($query->count() > 0) {
            $judge = $query->first();
            $checkPassword = $judge->password == $password;
            if ($checkPassword) {
                $token = sha1($judge->judge_id."-".rand(1,10000));
                Judge::where("judge_id", $judge->judge_id)->update(["token" => $token]);
                return response([
                    "logged" => true,
                    "message" => "Log In Success",
                    "data" => $judge,
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
        if ($limit == null && $offset == null) {
            return response(Judge::all());
        } else {
            return response(Judge::take($limit)->skip($offset)->get());
        }
    }

    public function find(Request $request, $limit = null, $offset = null)
    {
        $find = $request->find;
        $query = Judge::where("judge_name","like","%$find%")->orWhere("username","like","%$find%");

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
                Judge::create([
                    "judge_id" => "JID".time(),
                    "judge_name" => $request->judge_name,
                    "username" => $request->username,
                    "password" => sha1($request->password).md5($request->password),
                    "token" => ""
                ]);
            } else if ($action == "update") {
                if ($request->has("password")){
                    Judge::where("judge_id", $request->judge_id)->update([
                        "judge_name" => $request->judge_name,
                        "username" => $request->username,
                        "password" => sha1($request->password).md5($request->password)
                    ]);
                }else{
                    Judge::where("judge_id", $request->judge_id)->update([
                        "judge_name" => $request->judge_name,
                        "username" => $request->username
                    ]);
                }
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($judge_id)
    {
        try {
            Judge::where("judge_id",$judge_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
