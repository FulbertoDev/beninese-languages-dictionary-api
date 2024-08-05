<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuggestionResource;
use App\Models\Audio;
use App\Models\Expression;
use App\Models\Suggestion;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SuggestionController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|numeric',
            'message' => Rule::requiredIf($request->get('word') == null),
            'word.inFrench' => Rule::requiredIf($request->get('word') != null),
            'word.inFongbe' => Rule::requiredIf($request->get('word.inYoruba') == null && $request->get('word') != null),
            'word.inYoruba' => Rule::requiredIf($request->get('word.inFongbe') == null && $request->get('word') != null),
            'word.expressions' => 'array',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        $data = $request->all();

        $wordId = 0;

        if ($request->has('word')) {
            //Save word
            $word = new Word();
            $word->inFrench = $request->input('word.inFrench');
            $word->inFongbe = $request->input('word.inFongbe');
            $word->inYoruba = $request->input('word.inYoruba');
            $word->isValidated = false;
            $word->saveOrFail();
            $wordId = $word->id;

            //Audio
            $audio = new Audio();
            $audio->word_id = $word->id;
            $audio->save();

            //Expressions
            foreach ($data['word']['expressions'] as $item) {
                $expression = new Expression();
                $expression->inFrench = $item['inFrench'];
                $expression->inFongbe = $item['inFongbe'];
                $expression->inYoruba = $item['inYoruba'];
                $expression->word_id = $word->id;
                $expression->saveOrFail();
            }

        }

        $suggestion = new Suggestion();
        $suggestion->name = $request->input('name');
        $suggestion->email = $request->input('email');
        $suggestion->contact = $request->input('contact');
        $suggestion->message = $request->input('message');
        if ($request->has('word')) {
            $suggestion->word_id = $wordId;
        }
        $suggestion->saveOrFail();


        return response()->json(['status' => 'OK', 'message' => 'Suggestion submitted']);
    }


    public function getSuggestions()
    {
        $suggestions = Suggestion::all();
        return response()->json(SuggestionResource::collection($suggestions));

    }
}
