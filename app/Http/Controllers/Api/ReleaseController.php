<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Release;
use Illuminate\Http\Request;
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
            $content = array_merge($words, $latestRelease[0]->details['content']);
            $noChanges = $latestRelease[0]->details['content'] == $words;
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
                "content" => $nextContent,
            ]
        ]);
        return response()->json($release);

    }


    public function getReleases()
    {
        //$releases = Release::query()->orderBy('versionCode','desc')->get();
        $releases = Release::latest('versionCode')->get();
        return response()->json($releases);
    }

}
