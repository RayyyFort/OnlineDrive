<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

    public function CreateNew(Request $request){
        $id = $request->user()->id;
        $tempPath = str_replace("Root","",sprintf('temp_%s\\%s\\',$id,$request->__get("path")));
        $tempDisk = Storage::disk('temp');
        switch ($request->__get("Type")){
            case "Folder":
                $tempPath = sprintf("%sNew Folder",$tempPath);
                if (!file_exists($tempDisk->path($tempPath))){
                    mkdir($tempDisk->path($tempPath), 0777, true);
                }
                else{
                    $NotExist = true;
                    $count = 0;
                    while ($NotExist){
                        if (!file_exists(sprintf("%s(%s)",$tempDisk->path($tempPath),$count))){
                            mkdir(sprintf("%s(%s)",$tempDisk->path($tempPath),$count), 0777, true);
                            $NotExist = false;
                        }
                        $count++;
                    }
                }
                break;
            case "txt":
                $tempPath = sprintf("%sNew Text Document.txt",$tempPath);
                if (!file_exists($tempDisk->path($tempPath))){
                    file_put_contents($tempDisk->path($tempPath), "");
                }
                else{
                    $NotExist = true;
                    $count = 0;
                    while ($NotExist){
                        if (!file_exists(sprintf("%s(%s)",$tempDisk->path($tempPath),$count))){
                            file_put_contents(sprintf("%s(%s)",$tempDisk->path($tempPath),$count),"");
                            $NotExist = false;
                        }
                        $count++;
                    }
                }
                break;
        }
        return;
    }

    public function Delete(Request $request){
        $id = $request->user()->id;
        $tempPath = str_replace("Root","",sprintf('temp_%s\\%s\\',$id,$request->__get("path")));
        $tempDisk = Storage::disk('temp');
        if (is_dir($tempDisk->path($tempPath))){
            $this->rrmdir($tempDisk->path($tempPath));
        }
        else if (is_file($tempDisk->path($tempPath))){
            $link = $tempDisk->path($tempPath);
            unlink($link);
        }
    }

    public function DownloadFile(Request $request){
        $id = $request->user()->id;
        $tempPath = str_replace("Root","",sprintf('temp_%s%s',$id,$request->__get("path")));
        $tempDisk = Storage::disk('temp');
        $fileContent = $tempDisk->get($tempPath);
        if (file_exists($tempDisk->path($tempPath))){
            if (is_file($tempDisk->path($tempPath))){
                return response($fileContent)->header('Content-Type', mime_content_type($tempDisk->path($tempPath)));
            }
            else{
                $zipcreated = explode("\\",$tempPath)[count(explode("\\",$tempPath))-1] . ".zip";

                $rootPath = realpath($tempDisk->path($tempPath));

                $zip = new \ZipArchive();
                $zip->open(storage_path() . "\\" . $zipcreated, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($rootPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file)
                {
                    if (!$file->isDir())
                    {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                $zip->close();

                $fileUrl = Storage::url($zipcreated);

                return response()->download(storage_path($zipcreated), $zipcreated,  array('Content-Type' => 'application/octet-stream','Content-Length: '. filesize(storage_path($zipcreated))));
            }
        }
        else{
            return "not worfjhaskgfhafl";
        }
    }
}