<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quiz extends Model
{
	use HasFactory;

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)->withPivot(['time', 'result']);
	}

	public function questions(): BelongsToMany
	{
		return $this->belongsToMany(Question::class, 'answer_question_quiz');
	}
}
