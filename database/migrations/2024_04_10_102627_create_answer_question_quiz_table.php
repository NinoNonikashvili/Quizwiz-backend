<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('answer_question_quiz', function (Blueprint $table) {
			$table->id();
			$table->foreignId('quiz_id')->default(1)->constrained();
			$table->foreignId('question_id')->constrained();
			$table->foreignId('answer_id')->default(1)->constrained();
			$table->string('answer_status')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('answer_question_quiz');
	}
};
