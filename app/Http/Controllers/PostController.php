<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function post(Request $req){
        $status = $req->get('status');
    
        dd($status);
    }
    
    public function index(){
        return view('home');
    }
    
}
