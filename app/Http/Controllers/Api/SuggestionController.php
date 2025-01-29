<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuggestionResource;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuggestionController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|string',
            'deviceUuid' => 'required|string',
            'wordId' => 'string|nullable',
            'data.inFrench' => 'required',
            'data.expressions' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }


        $suggestion = new Suggestion();
        $suggestion->name = $request->input('name');
        $suggestion->email = $request->input('email');
        $suggestion->contact = $request->input('contact');
        if($request->has('wordId')){
            $suggestion->word_id = $request->input('wordId');
        }
        $suggestion->deviceUuid = $request->input('deviceUuid');
        $suggestion->data = json_encode($request->input('data'));
        $suggestion->saveOrFail();

        return response()->json(['status' => 'OK', 'message' => 'Suggestion submitted']);

    }


    public function getSuggestions()
    {
        $suggestions = Suggestion::all();
        return response()->json(SuggestionResource::collection($suggestions));

    }

    public function getSuggestionByDevice(Request $request, string $id)
    {
        $suggestions = Suggestion::whereDeviceuuid($id)->get();
        return response()->json(SuggestionResource::collection($suggestions));

    }
}
