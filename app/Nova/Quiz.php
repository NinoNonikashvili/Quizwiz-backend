<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quiz extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Quiz>
	 */
	public static $model = \App\Models\Quiz::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'title';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'title',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return array
	 */
	public function fields(NovaRequest $request)
	{
		return [
			ID::make()->sortable(),
			Text::make('title')->sortable()->rules('required'),
			Text::make('excerpt')->rules('required'),
			Text::make('instructions')->rules('required'),
			Number::make('time')->rules('required', ),
			Image::make('image')->preview(function ($value) {
				return asset('/storage/' . $value);
			})->rules('required'),
			DateTime::make('created_at'),
			BelongsTo::make('Level')->rules('required'),
			BelongsToMany::make('Categories', 'categories', Category::class)->rules('required'),
			BelongsToMany::make('Users')->fields(function ($request, $relatedModel) {
				return [
					Text::make('Time'),
					Text::make('Result'),
				];
			})->rules('required'),
			HasMany::make('Question', 'questions', Question::class)->rules('required'),
		];
	}
}
