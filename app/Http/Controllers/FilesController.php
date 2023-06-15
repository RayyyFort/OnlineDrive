<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    public function Index(Request $request,string $PathToGo = NULL){
        $id = $request->user()->id;
        $path = sprintf('D:\%s.zip',$id);
        $tempPath = sprintf('D:\temp_%s\\',$id);
        $Root = $tempPath;
        if(!file_exists($tempPath)){
            mkdir($tempPath, 0777, true);
            if(file_exists($path)){
                $zip = new \ZipArchive;
                $res = $zip->open($path);
                if ($res === TRUE) {
                    $zip->extractTo($tempPath);
                    $zip->close();
                } 
                else {
                    return "something went wrong";
                }
            }
        }
        if ($PathToGo != NULL){
            $tempPath = str_replace("Root",$tempPath,$PathToGo);
            $skip = 1;
            if ($PathToGo == "Root"){
                $skip = 2;
            }
            $FilesArr = array_slice(scandir($tempPath),$skip,NULL,false);
            $dirArr = array();
            foreach ($FilesArr as $value){
                array_push($dirArr,is_dir(sprintf("%s\\%s",$tempPath,$value)));
            }
            return array($FilesArr, $dirArr);
        }
        /*
        $zip = new \ZipArchive;
        if ($zip->open($path) === TRUE){
            $zip->extractTo($tempPath);
            $zip->close();
        }
        */
        $FilesArr = array_slice(scandir($tempPath),2,NULL,false);
        $dirArr = array();
        foreach ($FilesArr as $value){
            array_push($dirArr,is_dir(sprintf("%s\\%s",$tempPath,$value)));
        }
        return Inertia::render('Files', ['logged' => Auth::check(),'FilesList' => $FilesArr, 'CurrentFolder' => str_replace($Root,"Root",$tempPath), 'dirArr' => $dirArr]);
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)){
            $objects = scandir($dir);
            foreach ($objects as $object){
                if ($object != '.' && $object != '..'){
                    if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
                    else {unlink($dir.'/'.$object);}
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function FileChanged(){

    }

    public function DeleteTemp(){
        $dirname=sprintf("D:\\temp_%s",\Auth::id());
        $this->rrmdir($dirname);
    }

    public function Heartbeat(){
        $id = Auth::id();
        $data = array('id'=>$id);

        DB::beginTransaction();

        try {
            DB::table('active')->insert($data);
        
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }
}