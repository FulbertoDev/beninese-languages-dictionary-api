<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReducedReleaseResource;
use App\Http\Resources\ReleaseResource;
use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class ReleaseController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version' => 'required|string|max:255|unique:releases,versionName',
            'words' => 'required|array',
            'description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $latestRelease = Release::query()->orderBy('versionCode', 'desc')->get();
        $nextIndex = 0;

        if (count($latestRelease) == 0) {
            $nextIndex += 1;
        } else {
            $last = $latestRelease[0]->versionCode;
            $nextIndex = $last + 1;
        }


        $release = Release::create([
            "versionCode" => $nextIndex,
            "versionName" => $request->get('version'),
            "description" => $request->get('description'),
        ]);

        if ($request->user() != null && $request->user()->hasAnyRole(array(RolesEnum::ADMIN_ROLE, RolesEnum::HELPER_ROLE))) {
            return response()->json(ReleaseResource::make($release));
        } else {
            return response()->json(ReducedReleaseResource::make($release));
        }

    }


    /**
     * @unauthenticated
     */
    public function getReleases(Request $request)
    {
        $releases = Release::latest('versionCode')->get();
        return response()->json(ReleaseResource::collection($releases));
    }


}
