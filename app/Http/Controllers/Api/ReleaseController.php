<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReducedReleaseResource;
use App\Http\Resources\ReleaseResource;
use App\Jobs\ProcessWordRelease;
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
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $latestRelease = Release::query()->orderBy('versionCode', 'desc')->get();
        $nextIndex = 0;
        $content = array();

        $noChanges = false;

        if (count($latestRelease) == 0) {
            $nextIndex += 1;
            $content = array_merge($request->get("words"));
        } else {
            $last = $latestRelease[0]->versionCode;
            $nextIndex = $last + 1;
            $words = $request->get("words");
            $content = array_merge($words, explode(";", $latestRelease[0]->details['content']));
            $noChanges = explode(";", $latestRelease[0]->details['content']) == $words;
        }

        if ($noChanges) {
            return response()->json([
                'error' => 'Nothing has changed with the previous version'
            ], 400);
        }


        $nextContent = array_unique($content);
        $wordsCount = count($nextContent);


        $release = Release::create([
            "versionCode" => $nextIndex,
            "versionName" => $request->get('version'),
            "details" => [
                "count" => $wordsCount,
                "content" => join(";", $nextContent),
            ]
        ]);

        Artisan::call('app:update-word-status', ['words' => $release->details['content']]);

        if ($request->user()->hasAnyRole(array(RolesEnum::ADMIN_ROLE, RolesEnum::HELPER_ROLE))) {
            return response()->json(ReleaseResource::make($release));
        } else {
            return response()->json(ReducedReleaseResource::make($release));
        }


    }


    public function getReleases(Request $request)
    {
        $releases = Release::latest('versionCode')->get();
        if ($request->user()->hasAnyRole(array(RolesEnum::ADMIN_ROLE, RolesEnum::HELPER_ROLE))) {
            return response()->json(ReleaseResource::collection($releases));
        } else {
            return response()->json(ReducedReleaseResource::collection($releases));
        }

    }

}
