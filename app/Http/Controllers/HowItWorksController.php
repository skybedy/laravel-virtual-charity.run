<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HowItWorksController extends Controller
{
    public function index()
    {
            return view('howitworks/index');
        
        
    }
}
