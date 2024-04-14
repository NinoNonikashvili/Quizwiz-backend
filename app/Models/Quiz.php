<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

	public function scopeWithFilters(Builder $query): void
	{
		$query->when(request()->has('cat'), function ($query) {
			$query->whereHas('categories', function ($query) {
				$query->whereIn('category_id', request()->input('cat'));
			});
		})
		->when(request()->has('level'), function ($query) {
			$query->where('level_id', request()->input('level'));
		})
		->when(request()->has('my_quizes'), function ($query) {
			$query->whereHas('users', function ($query) {
				if (request()->input('my_quizes') === 'true') {
					$query->where('user_id', auth()->user()->id);
				} else {
					$query->whereNot(function ($query) {
						$query->where('user_id', auth()->user()->id);
					});
				}
			});
		})
		->when(request()->has('alp_sort'), function ($query) {
			$query->orderBy('title', request()->input('alp_sort'));
		})
		->when(request()->has('date_sort'), function ($query) {
			$query->orderBy('created_at', request()->input('date_sort'));
		})->when(request()->has('popular_sort'), function ($query) {
			$query->orderBy('users_count', 'desc');
		})
		->when(auth()->check(), function ($query) {
			$query->with('users', function ($query) {
				$query->where('user_id', auth()->user()->id);
			});
		});
	}
}
