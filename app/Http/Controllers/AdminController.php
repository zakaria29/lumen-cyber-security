<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Admin;


class AdminController extends Controller
{

    public function authenticate(Request $request)
    {
        $username = $request->username;
        $password = sha1($request->password).md5($request->password);
        
        $query = Admin::where("username", $username);
        if ($query->count() > 0) {
            $admin = $query->first();
            $checkPassword = $admin->password == $password;
            if ($checkPassword) {
                $token = sha1($admin->admin_id."-".rand(1,10000));
                Admin::where("admin_id", $admin->admin_id)->update(["token" => $token]);
                return response([
                    "logged" => true,
                    "message" => "Log In Success",
                    "data" => $admin,
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
            return response(Admin::all());
        } else {
            return response(Admin::take($limit)->skip($offset)->get());
        }
    }

    public function find(Request $request, $limit = null, $offset = null)
    {
        $find = $request->find;
        $query = Admin::where("admin_name","like","%$find%")->orWhere("username","like","%$find%");

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
                Admin::create([
                    "admin_id" => "AID".time(),
                    "admin_name" => $request->admin_name,
                    "username" => $request->username,
                    "password" => sha1($request->password).md5($request->password),
                    "token" => ""
                ]);
            } else if ($action == "update") {
                if ($request->has("password")){
                    Admin::where("admin_id", $request->admin_id)->update([
                        "admin_name" => $request->admin_name,
                        "username" => $request->username,
                        "password" => sha1($request->password).md5($request->password)
                    ]);
                }else{
                    Admin::where("admin_id", $request->admin_id)->update([
                        "admin_name" => $request->admin_name,
                        "username" => $request->username
                    ]);
                }
            }
            
            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($admin_id)
    {
        try {
            Admin::where("admin_id",$admin_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
