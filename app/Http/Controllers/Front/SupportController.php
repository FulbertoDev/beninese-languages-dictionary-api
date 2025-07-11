<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->input('action');
        return view('support', compact('action'));

    }

    public function thanks(Request $request)
    {
        return view('thanks');
    }
}
