<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidatedWordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "inFrench" => $this->inFrench,
            "inFongbe" => $this->inFongbe,
            "inYoruba" => $this->inYoruba,
            "inBariba" => $this->inBariba,
            "inAdja" => $this->inAdja,
            "inBatonou" => $this->inBatonou,
            "inDendi" => $this->inDendi,
            "inDitamari" => $this->inDitamari,
            "inFulfulde" => $this->inFulfulde,
            "inGengbe" => $this->inGengbe,
            "inGungbe" => $this->inGungbe,
            "inYom" => $this->inYom,
            "isValidated" => $this->isValidated,
            "expressions" => ExpressionResource::collection(
                $this->expressions->where('isValidated', true)
            ),
            "audio" => AudioResource::make($this->audio)
        ];
    }
}
