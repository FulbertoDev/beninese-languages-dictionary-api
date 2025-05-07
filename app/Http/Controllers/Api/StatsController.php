<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaymentReasonEnum;
use App\Helpers\PaymentStatusEnum;
use App\Helpers\SuggestionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Suggestion;
use App\Models\Word;

class StatsController extends Controller
{
    public function index()
    {
        //Words
        $wordsCount = Word::count();
        $validatedWordsCount = Word::whereIsvalidated(true)->count();

        //Payments
        $payments = Payment::whereStatus(PaymentStatusEnum::CONFIRMED)->whereReason(PaymentReasonEnum::SUBSCRIPTION)->sum('amount');
        $gifts = Payment::whereStatus(PaymentStatusEnum::CONFIRMED)->whereReason(PaymentReasonEnum::GIFT)->sum('amount');


        //Suggestions
        $validatedSuggestionsCount = Suggestion::whereStatus(SuggestionStatusEnum::VALIDATED)->count();
        $pendingSuggestionsCount = Suggestion::whereStatus(SuggestionStatusEnum::PENDING)->count();
        $rejectedSuggestionsCount = Suggestion::whereStatus(SuggestionStatusEnum::REJECTED)->count();

        return response()->json([
            "words" => $validatedWordsCount,
            "suggestions" => [
                "total" => $validatedSuggestionsCount + $pendingSuggestionsCount + $rejectedSuggestionsCount,
                "validated" => $validatedSuggestionsCount,
                "pending" => $pendingSuggestionsCount,
                "rejected" => $rejectedSuggestionsCount,
            ],
            "payments" => (int)$payments,
            "gifts" => (int)$gifts,
        ]);
    }
}
