<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpressionResource;
use App\Http\Resources\PendingExpressionResource;
use App\Models\Expression;
use App\Models\PendingExpression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpressionController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_id' => 'required|string',
            'inFrench' => 'string|required',
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
        $expression = Expression::create(attributes: $request->all());

        return response()->json([
            'message' => 'Expression added succesfully',
            'data'=> ExpressionResource::make($expression)
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

        $pendingExpression = PendingExpression::findOrFail($id);

        if (!isset($pendingExpression)) {
            return response()->json([
                'message' => 'Pending expression not found'
            ], 404);
        }

        $pendingExpression->inFrench = $request->inFrench;
        $pendingExpression->inFongbe = $request->inFongbe;
        $pendingExpression->inYoruba = $request->inYoruba;
        $pendingExpression->inBariba = $request->inBariba;
        $pendingExpression->inAdja = $request->inAdja;
        $pendingExpression->inBatonou = $request->inBatonou;
        $pendingExpression->inDendi = $request->inDendi;
        $pendingExpression->inDitamari = $request->inDitamari;
        $pendingExpression->inFulfulde = $request->inFulfulde;
        $pendingExpression->inGengbe = $request->inGengbe;
        $pendingExpression->inGungbe = $request->inGungbe;
        $pendingExpression->inYom = $request->inYom;

        $pendingExpression->save();

        return response()->json([
            'message' => 'Pending expression updated successfully',
            'word' => PendingExpressionResource::make($pendingExpression)
        ]);

    }

    public function show(Request $request, $id)
    {
        $pendingExpression = PendingExpression::where('expression_id', $id)->get();

        if (count($pendingExpression) == 0) {
            return response()->json([
                'message' => 'Expression not found'
            ], 404);
        }
        $pendingExpression = $pendingExpression->first();
        return response()->json(PendingExpressionResource::make($pendingExpression));
    }


    public function countPendingExpressions(Request $request)
    {
        $count = PendingExpression::count();
        return response()->json($count);
    }

}
