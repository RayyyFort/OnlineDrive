<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// echo("<script>console.log('PHP: " . $data . "');</script>");

class FilesController extends Controller
{
    public function Index(Request $request,string $PathToGo = NULL){
        $tempDisk = Storage::disk('temp');
        $CompressedDisk = Storage::disk('Compressed');
        $id = $request->user()->id;
        $path = sprintf('%s.zip',$id);
        $tempPath = sprintf('temp_%s\\',$id);
        $Root = $tempPath;
        if(!$tempDisk->exists($tempPath)){
            $tempDisk->makeDirectory($tempPath);
            if($CompressedDisk->exists($path)){
                $zip = new \ZipArchive;
                $res = $zip->open($CompressedDisk->path($path));
                if ($res === TRUE) {
                    $zip->extractTo($tempDisk->path($tempPath));
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
            $FilesArr = array_map(fn($longPath) => basename($longPath),$tempDisk->files($tempPath));
            //$FilesArr = array_slice(scandir($tempPath),$skip,NULL,false);
            $dirArr = array_map(fn($longPath) => basename($longPath),$tempDisk->directories($tempPath));
            return array($FilesArr, $dirArr);
        }
        
        $FilesArr = array_map(fn($longPath) => basename($longPath),$tempDisk->files($tempPath));
        //$FilesArr = array_slice(scandir($tempPath),2,NULL,false);
        $dirArr = array_map(fn($longPath) => basename($longPath),$tempDisk->directories($tempPath));
        return Inertia::render('Files', ['logged' => Auth::check(),'FilesArr' => $FilesArr, 'CurrentFolder' => str_replace($Root,"Root",$tempPath), 'dirArr' => $dirArr]);
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)){
            $objects = scandir($dir);
            foreach ($objects as $object){
                if ($object != '.' && $object != '..'){
                    if (filetype($dir.'/'.$object) == 'dir') {$this->rrmdir($dir.'\\'.$object);}
                    else {unlink($dir.'/'.$object);}
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function FileChanged(Request $request){
        $id = $request->user()->id;
        $tempPath = sprintf('temp_%s\\%s\\',$id,$request->path);
        $tempDisk = Storage::disk('temp');
        $file = $request->allFiles();
        foreach ($file as $curr){
            if (!file_exists($tempDisk->path($tempPath))){
                mkdir($tempDisk->path($tempPath), 0777, true);
            }
            file_put_contents(sprintf("%s\\%s",$tempDisk->path($tempPath),$curr->getClientOriginalName()),file_get_contents($curr));
        }
        return;
    }

    public function DeleteTemp(){
        $dirname=sprintf("D:\\temp\\temp_%s",\Auth::id());
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