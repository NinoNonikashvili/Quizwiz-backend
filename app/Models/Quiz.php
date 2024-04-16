<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	use HasFactory;

	protected $table = 'quizes';

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

	public function questions(): HasMany
	{
		return $this->HasMany(Question::class);
	}

	public function scopeWithFilters(Builder $query): void
	{
		$query->when(request()->has('cat'), function ($query) {
			$query->whereHas('categories', function ($query) {
				$query->whereIn('category_id', request()->input('cat'));
			});
		})
		->when(request()->has('level'), function ($query) {
			$query->whereIn('level_id', request()->input('level'));
		})
		->when(request()->has('sort_alphabet'), function ($query) {
			$query->orderBy('title', request()->input('sort_alphabet'));
		})
		->when(request()->has('sort_date'), function ($query) {
			$query->orderBy('created_at', request()->input('sort_date'));
		})->when(request()->has('sort_popular'), function ($query) {
			$query->orderBy('users_count', 'desc');
		});
	}

	public function scopeWithUserQuizes(Builder $query)
	{
		$query->when(request()->has('my_quizes') && auth()->check(), function ($query) {
			$query->whereHas('users', function ($query) {
				if (request()->input('my_quizes') === 'true') {
					$query->where('user_id', auth()->user()->id);
				} else {
					$query->whereNot(function ($query) {
						$query->where('user_id', auth()->user()->id);
					});
				}
			});
		});
	}

	public function scopeWithUserResults(Builder $query)
	{
		$query->when(auth()->check(), function ($query) {
			$query->with('users', function ($query) {
				$query->where('user_id', auth()->user()->id);
			});
		});
	}

	public function scopeWithSimilar(Builder $query, Quiz $quiz)
	{
		$query->whereHas('categories', function (Builder $query) use ($quiz) {
			$query->whereIn('category_id', $quiz->categories->pluck('id'));
		})->with(['categories', 'level'])->when(auth()->check(), function (Builder $query) {
			$query->whereDoesnthave('users', function (Builder $query) {
				$query->where('user_id', auth()->user()->id);
			});
		});
	}
}
