<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    public function Index(){
        return Inertia::render('Files', ['logged' => Auth::check()]);
    }

    public function FileChanged(){
        
    }
}
