<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class SupportController extends Controller
{
   public function index(Request $request)
    {
        $token = $request->input('token');
        if(isset($token)){
            return view('support');
        }
        return redirect('https://www.iamyourclounon.bj/');
    }
}
