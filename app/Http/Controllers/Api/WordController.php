<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AuthorizedUserAgents;
use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Models\Audio;
use App\Models\Expression;
use App\Models\Release;
use App\Models\Word;
use Dedoc\Scramble\Attributes\HeaderParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WordController extends Controller
{

    /**
     * @unauthenticated
     */
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

    public function import(Request $request)
    {
        $file = Storage::disk('local')->get('json/words.json');
        $json = json_decode($file, true);

        foreach ($json as $item) {
            $word = new Word();
            $word->inFrench = trim($item['inFrench']);
            $word->inFongbe = trim($item['inFongbe']);
            $word->inYoruba = (isset($item['inYoruba']) && $item['inYoruba'] != '') ? $item['inYoruba'] : null;
            $word->isValidated = true;
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
            "description" => 'Déploiement Initial'
        ]);

        $free = Word::take(100)->get();

        return response()->json([
            "success" => 'OK',
            "message" => $count . ' mots importés avec succès',
            "initialWords" => WordResource::collection($free),
        ]);
    }

    #[HeaderParameter('User-Agent', 'User-Agent', type: 'string')]
    public function getWords(Request $request)
    {
        $userAgent = $request->header('user-agent');
        if ($userAgent == null) {
            return response()->json(null, 400);
        }
        if ($userAgent != AuthorizedUserAgents::dart_3_8) {
            return response()->json(null, 400);
        }
        if ($userAgent != AuthorizedUserAgents::dart_3_10_4) {
            return response()->json(null, 400);
        }
        /*
        if (!in_array($userAgent, AuthorizedUserAgents::authorizedUserAgents)) {
            return response()->json(null, 400);
        }*/

        $perPage = $request->query('per_page', 10);
        $searchInFrench = $request->query('searchInFrench');
        $searchInFongbe = $request->query('searchInFongbe');

        $words = Word::orderBy('inFrench')
            ->whereIsvalidated(true)
            ->when($searchInFongbe, function ($query, $search) {
                return $query->where('inFongbe', 'like', "%{$search}%");
            })
            ->when($searchInFrench, function ($query, $search) {
                return $query->where('inFrench', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return WordResource::collection($words);

    }
}
