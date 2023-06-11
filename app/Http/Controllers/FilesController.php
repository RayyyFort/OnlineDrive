<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    public function Index(Request $request){
        $id = $request->user()->id;
        $path = sprintf('D:\%s.zip',$id);
        $tempPath = sprintf('D:\temp_%s',$id);
        if (!file_exists($path)){
            $myfile = fopen($path, "w");
            fclose($myfile);
            #try{
            #    $phar = new PharData($path);
            #    $phar->extractTo($temp,$overwrite=true);
            #}
            #catch(Exception $e){
            #    return Inertia::render('About', ['logged' => Auth::check()]);
            #}
        }
        $zip = new \ZipArchive;
        if ($zip->open($path) === TRUE){
            $zip->extractTo($tempPath);
            $zip->close();
        }
        $FilesArr = array_slice(scandir($tempPath),1,NULL,false);
        return Inertia::render('Files', ['logged' => Auth::check(),'FilesList' => $FilesArr]);
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