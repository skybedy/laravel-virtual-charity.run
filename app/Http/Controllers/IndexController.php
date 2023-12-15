<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        
        $time1 = new Carbon('2023-12-02T14:29:22.000Z');
        $timestamp1 = $time1->timestamp;
        
        $time2 = new Carbon('2023-12-02T14:29:22Z');
        $timestamp2 = $time2->timestamp;
        
       // dump($timestamp1);
        //dd($timestamp2);        
        
        
        
        return view('index');
    }
}
