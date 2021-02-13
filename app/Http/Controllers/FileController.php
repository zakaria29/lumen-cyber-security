<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

use App\File;


class FileController extends Controller
{

    public function save(Request $request)
    {
        try {
            $count = count($request->file("files"));
            for ($i=0; $i < $count ; $i++) { 
                $file = $request->file("files")[$i];
                $fileName = time()."-".$file->getClientOriginalName();
                $file->move(storage_path('files'), $fileName);

                File::create([
                    "file_id" => "FL".time().rand(1,10000),
                    "file_name" => $fileName,
                    "question_id" => $request->question_id
                ]);
            }

            return response(["status" => true, "message" => "Data has been saved"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function drop($file_id)
    {
        try {
            $files = File::where("file_id", $file_id)->first();

            $path = storage_path("files/".$files->file_name);
            if(file_exists($path)){
                unlink($path);
            }

            File::where("file_id",$file_id)->delete();
            return response(["status" => true, "message" => "Data has been deleted"]);
        } catch (\Exception $e) {
            return response(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
