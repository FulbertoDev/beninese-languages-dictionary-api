<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstallationController extends Controller
{

    /**
     * @unauthenticated
     */
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

    public function checkDeviceSubscription(Request $request, string $id)
    {
        $headers = $request->headers->all();
        $userAgent = $request->header('user-agent');
        if ($userAgent != "Dart 3.8 (dart:io)") {
            Log::info("Not from device");
            return response()->json(null, 400);
        }
        Log::info("Launched from device");

        Log::info("Headers: " . json_encode($headers));
        $installation = Installation::findOrFail($id);
        return response()->json([
            "deviceUuid" => $installation->id,
            "hasSubscribed" => (bool)$installation->hasSubscribed,
        ]);
    }
}
