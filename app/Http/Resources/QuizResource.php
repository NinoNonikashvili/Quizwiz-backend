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
		$time = null;
		$result = null;
		$completed = false;
		$date = null;
		if (auth()->user()) {
			$user = $this->users->filter(function ($obj) {
				return $obj->id === 1;
			});
			$completed = true;
			$time = $user->time;
			$result = $user->result;
			$date = $user->created_at;
		}

		return [
			'id'                 => $this->id,
			'title'              => $this->title,
			'image'              => $this->image,
			'level'              => $this->level,
			'categories'         => $this->categories,
			'total_users'        => $this->users->count(),
			'total_time'         => $time,
			'points'             => $result,
			'completed'          => $completed,
			'date'               => $date,
		];
	}
}
