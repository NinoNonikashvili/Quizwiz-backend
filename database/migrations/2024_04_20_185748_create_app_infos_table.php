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
		Schema::create('app_infos', function (Blueprint $table) {
			$table->id();
			$table->mediumText('email');
			$table->mediumText('phone');
			$table->mediumText('facebook');
			$table->mediumText('instagram');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('app_infos');
	}
};
