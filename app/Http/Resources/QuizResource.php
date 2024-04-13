<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                          => $this->id,
			'title'                       => $this->title,
			'image'                       => $this->image,
			'level'                       => $this->whenLoaded('level'),
			'categories'                  => $this->whenLoaded('categories'),
			'total_users'                 => $this->whenCounted('users'),
			'points'                      => $this->whenLoaded('users', function () {
				return $this->users->first()->pivot->result;
			}, null),
			'completed'                   => $this->whenLoaded('users', function () {
				return !empty($this->users);
			}, false),
			'date'                        => $this->whenLoaded('users', function () {
				return $this->users->first()->pivot->created_at;
			}, null),
			'total_time'                  => $this->whenLoaded('users', function () {
				return $this->users->first()->pivot->time;
			}, null),
		];
	}
}
