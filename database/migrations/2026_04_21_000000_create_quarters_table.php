<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quarters', function (Blueprint $table) {
			$table->id();
			$table->foreignId('grade_id')->constrained()->cascadeOnDelete();
			$table->unsignedTinyInteger('number');
			$table->timestamps();

			$table->unique(['grade_id', 'number']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quarters');
	}
};
