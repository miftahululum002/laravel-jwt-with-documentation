<?php

namespace App\Http\Controllers;

use App\Libraries\ResponseLibrary;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $res = ['version' => '0.1.0'];
        return ResponseLibrary::successResponse('Welcome to API Documentation', $res);
    }
}
