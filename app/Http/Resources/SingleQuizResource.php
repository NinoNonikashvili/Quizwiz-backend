<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleQuizResource extends JsonResource
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
			'image'                       => asset('storage/' . $this->image),
			'level'                       => $this->level,
			'categories'                  => $this->categories,
			'excerpt'                     => $this->excerpt,
			'instructions'                => $this->instructions,
			'time'                        => $this->time,
			'total_users'                 => $this->users->count(),
			'questions'                   => $this->questions->count(),
			'points'                      => $this->questions->reduce(function ($carry, $question) {
				return $carry + $question['points'];
			}),
			'has_user_written'=> $this->when(auth()->check(), function () {
				return auth()->user()->quizes->contains('id', $this->id);
			}),
		];
	}
}
