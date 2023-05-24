<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //

    public function dbtest(){ 
        $pdo=DB::connection()->getPdo(); dd($pdo);
     }
}
