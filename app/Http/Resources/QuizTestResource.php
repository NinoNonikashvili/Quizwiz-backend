<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizTestResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'             => $this->id,
			'question'       => $this->question,
			'points'         => $this->points,
			'correct_answers'=> $this->correct_answers,
			'quiz_id'        => $this->quiz_id,
			'answers'        => AnswerResource::collection($this->whenLoaded('answers')),
		];
	}
}
