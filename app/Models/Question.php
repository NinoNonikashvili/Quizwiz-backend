<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
	use HasFactory;

	public function answers(): BelongsToMany
	{
		return $this->belongsToMany(Answer::class, 'answer_question_quiz');
	}

	public function quizes(): BelongsToMany
	{
		return $this->belongsToMany(Quiz::class, 'answer_question_quiz');
	}
}
