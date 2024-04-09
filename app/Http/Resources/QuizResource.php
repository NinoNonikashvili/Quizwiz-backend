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
			'title'        => $this->title,
			'instructions' => $this->instructions,
			'excerpt'      => $this->excerpt,
			'image'        => $this->image,
			'level_id'     => $this->level_id,
			'level'        => $this->level,
			'categories'   => $this->categories,
		];
	}
}
