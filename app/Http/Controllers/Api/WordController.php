<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AuthorizedUserAgents;
use App\Http\Controllers\Controller;
use App\Http\Resources\WordResource;
use App\Models\Audio;
use App\Models\Expression;
use App\Models\Release;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WordController extends Controller
{

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'inFrench' => 'required|string',
            'inYoruba' => 'string|nullable',
            'inFongbe' => 'string|nullable',
            'inBariba' => 'string|nullable',
            'inAdja' => 'string|nullable',
            'inBatonou' => 'string|nullable',
            'inDendi' => 'string|nullable',
            'inDitamari' => 'string|nullable',
            'inFulfulde' => 'string|nullable',
            'inGengbe' => 'string|nullable',
            'inGungbe' => 'string|nullable',
            'inYom' => 'string|nullable',
            'expressions' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $word = Word::create($request->all());


        if ($request->has('expressions')) {
            $expressions = $request->get('expressions'); // déjà un tableau d’éléments
            foreach ($expressions as $element) {
                $expression = new Expression();
                $expression->word_id = $word->id;
                $expression->inFrench = $element['inFrench'];
                $expression->inFongbe = $element['inFongbe'];
                $expression->inYoruba = $element['inYoruba'];
                $expression->inAdja = $element['inAdja'];
                $expression->inBariba = $element['inBariba'];
                $expression->inBatonou = $element['inBatonou'];
                $expression->inDendi = $element['inDendi'];
                $expression->inDitamari = $element['inDitamari'];
                $expression->inFulfulde = $element['inFulfulde'];
                $expression->inGengbe = $element['inGengbe'];
                $expression->inGungbe = $element['inGungbe'];
                $expression->inYom = $element['inYom'];
                $expression->save();
            }
        }

        $audio = new Audio();
        $audio->word_id = $word->id;
        $audio->save();

        $response = Word::find($word->id);

        return response()->json([
            'message' => 'Word created successfully',
            'word' => WordResource::make($response)
        ]);
    }



    /**
     * @unauthenticated
     */
    public function fetch(Request $request)
    {
        $userAgent = $request->header('user-agent');
        $isAuthorizedUserAgent = in_array($userAgent, AuthorizedUserAgents::authorizedUserAgents);
        $isAuthorizedOrigin = $request->header('origin') == AuthorizedUserAgents::authorizedOrigin;

        $isLocalEnv = env("APP_ENV", "production") == "local";

        if (!$isLocalEnv) {
            if (!$isAuthorizedUserAgent && !$isAuthorizedOrigin) {
                return response()->json([], 404);
            }
        }



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

    public function destroy($id)
    {
        $word = Word::findOrFail($id);

        if ($word->isValidated == true) {
            return response()->json([
                "message" => "Word is already validated"
            ], 403);
        }

        $expressions = Expression::whereWordId($word->id)->get();

        foreach ($expressions as $expression) {
            $expression->delete();
        }

        $audio = Audio::whereWordId($word->id)->first();
        $audio->delete();


        $word->delete();

        return response()->json([
            "message" => "Word successfully deleted"
        ],  200);
    }

    public function getWords(Request $request)
    {
        $userAgent = $request->header('user-agent');
        $isAuthorizedUserAgent = in_array($userAgent, AuthorizedUserAgents::authorizedUserAgents);
        $isAuthorizedOrigin = $request->header('origin') == AuthorizedUserAgents::authorizedOrigin;

        $isLocalEnv = env("APP_ENV", "local") == "local";


        if (!$isLocalEnv) {
            if (!$isAuthorizedUserAgent && !$isAuthorizedOrigin) {
                return response()->json([], 404);
            }
        }
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
