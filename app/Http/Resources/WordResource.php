<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
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
            "isValidated" => $this->isValidated,
            "expressions" => ExpressionResource::collection($this->expressions),
            "audio"=>AudioResource::make($this->audio)
        ];
    }
}
