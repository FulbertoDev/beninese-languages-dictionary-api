<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\PendingWordResource;
use App\Models\PendingWord;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendingWordController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_id' => 'required|string',
            'inFrench' => 'string|nullable',
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $word_id = $request->word_id;
        $inFrench = $request->inFrench;
        $inFongbe = $request->inFongbe;
        $inYoruba = $request->inYoruba;
        $inBariba = $request->inBariba;
        $inAdja = $request->inAdja;
        $inBatonou = $request->inBatonou;
        $inDendi = $request->inDendi;
        $inDitamari = $request->inDitamari;
        $inFulfulde = $request->inFulfulde;
        $inGengbe = $request->inGengbe;
        $inGungbe = $request->inGungbe;
        $inYom = $request->inYom;

        $word = Word::find($word_id);

        if (!isset($word)) {
            return response()->json([
                'message' => 'Word is required'
            ], 404);
        }

        $pendingWord = new PendingWord();
        $pendingWord->word_id = $word_id;
        $pendingWord->inFrench = $inFrench;
        $pendingWord->inFongbe = $inFongbe;
        $pendingWord->inYoruba = $inYoruba;
        $pendingWord->inBariba = $inBariba;
        $pendingWord->inAdja = $inAdja;
        $pendingWord->inBatonou = $inBatonou;
        $pendingWord->inDendi = $inDendi;
        $pendingWord->inDitamari = $inDitamari;
        $pendingWord->inFulfulde = $inFulfulde;
        $pendingWord->inGengbe = $inGengbe;
        $pendingWord->inGungbe = $inGungbe;
        $pendingWord->inYom = $inYom;
        $pendingWord->save();

        return response()->json([
            'message' => 'Word created successfully',
            'word' => PendingWordResource::make($pendingWord)
        ]);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'inFrench' => 'string|nullable',
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $pendingWord = PendingWord::findOrFail($id);

        if (!isset($pendingWord)) {
            return response()->json([
                'message' => 'Pending word not found'
            ], 404);
        }

        $pendingWord->inFrench = $request->inFrench;
        $pendingWord->inFongbe = $request->inFongbe;
        $pendingWord->inYoruba = $request->inYoruba;
        $pendingWord->inBariba = $request->inBariba;
        $pendingWord->inAdja = $request->inAdja;
        $pendingWord->inBatonou = $request->inBatonou;
        $pendingWord->inDendi = $request->inDendi;
        $pendingWord->inDitamari = $request->inDitamari;
        $pendingWord->inFulfulde = $request->inFulfulde;
        $pendingWord->inGengbe = $request->inGengbe;
        $pendingWord->inGungbe = $request->inGungbe;
        $pendingWord->inYom = $request->inYom;

        $pendingWord->save();

        return response()->json([
            'message' => 'Pending word updated successfully',
            'word' => PendingWordResource::make($pendingWord)
        ]);

    }


    public function show(Request $request, $id)
    {
        $pendingWord = PendingWord::where('word_id', $id)->count();
        $newWord = Word::whereIsvalidated(false)->where('id', $id)->count();

        if ($pendingWord == 0 && $newWord == 0) {
            return response()->json([
                'message' => 'Word not found ' . $id
            ], 404);
        }
        if ($pendingWord > 0) {
            $response = PendingWord::where('word_id', $id)->first();
        } else {
            $response = Word::where('id', $id)->first();
        }
        return response()->json(PendingWordResource::make($response));

    }

    public function countPendingWords(Request $request)
    {
        $count = PendingWord::count();
        return response()->json($count);
    }
}
