<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstallationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'model' => 'required|string',
            'systemName' => 'required|string',
            'systemVersion' => 'required|string',
            'brand' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $installation = Installation::create($request->all());
        return response()->json([
            "deviceUuid" => $installation->id
        ]);
    }

}
