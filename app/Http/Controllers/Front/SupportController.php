<?php

namespace App\Http\Controllers\Front;

use App\Helpers\DecryptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->input('token');
        if (!isset($token)) {
            return view('support');
        }
        $decrypted = DecryptionHelper::decryptUrlData($token);
        return view('support', compact('decrypted'));

    }

    public function thanks(Request $request)
    {
        return view('thanks');
    }
}
