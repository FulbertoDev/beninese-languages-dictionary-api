<?php

namespace App\Http\Controllers\Front;

use App\Helpers\DecryptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        return view('support');

    }

    public function thanks(Request $request)
    {
        return view('thanks');
    }
}
