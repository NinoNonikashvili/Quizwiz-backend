<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
	use HasFactory;

	public function quizes()
	{
		return $this->hasMany(Quiz::class);
	}

	public $timestamps = false;
}
