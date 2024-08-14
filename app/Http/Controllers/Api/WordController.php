<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Models\Audio;
use App\Models\Expression;
use App\Models\Release;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WordController extends Controller
{
    public function fetch()
    {
        $releaseCount = Release::all()->pluck('id')->count();
        if ($releaseCount <= 0) {
            return response()->json([], 404);
        }
        $words = Word::whereIsvalidated(true)->get();
        $count = $words->count();
        $latestRelease = Release::query()->orderBy('versionCode', 'desc')->get()->first();

        return response()->json(
            [
                "count" => $count,
                "version" => $latestRelease->versionCode,
                "data" => WordResource::collection($words),
            ]
        );
    }

    public function fetchPendingWords()
    {
        $words = Word::whereIsvalidated(false)->get();
        $count = $words->count();
        return response()->json(
            ["count" => $count, "data" => WordResource::collection($words),]
        );
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $file = request()->file('file');
        $content = file_get_contents($file);
        $json = json_decode($content, true);


        foreach ($json as $item) {
            $word = new Word();
            $word->inFrench = trim($item['inFrench']);
            $word->inFongbe = trim($item['inFongbe']);
            $word->inYoruba = (isset($item['inYoruba']) && $item['inYoruba'] != '') ? $item['inYoruba'] : null;
            $word->isValidated = rand(0, 1) < 1;
            $word->save();
            if ($item['expressions']) {
                foreach ($item['expressions'] as $element) {
                    $expression = new Expression();
                    $expression->word_id = $word->id;
                    $expression->inFrench = $element['inFrench'];
                    $expression->inFongbe = $element['inFongbe'];
                    $expression->inYoruba = (isset($element['inYoruba']) && $element['inYoruba'] != '') ? $element['inYoruba'] : null;
                    $expression->save();
                }
            }

            $audio = new Audio();
            $audio->word_id = $word->id;
            $audio->save();

        }

        $words = Word::whereIsvalidated(true)->pluck('id');
        $count = count($words);

        Release::create([
            "versionCode" => 1,
            "versionName" => '1.0',
            "details" => [
                "count" => $count,
                "content" => $words,
            ]
        ]);

        return response()->json([
            "success" => 'OK',
            "message" => $count . ' mots importés avec succès'
        ]);
    }

}
