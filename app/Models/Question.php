<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
	use HasFactory;

	public function answers(): HasMany
	{
		return $this->HasMany(Answer::class);
	}

	public function quiz(): BelongsTo
	{
		return $this->belongsTo(Quiz::class);
	}
}