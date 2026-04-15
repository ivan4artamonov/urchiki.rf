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
		Schema::create('tariffs', function (Blueprint $table) {
			$table->id();
			$table->string('name', 120);
			$table->text('description')->nullable();
			$table->unsignedSmallInteger('duration_days');
			$table->unsignedInteger('downloads_limit');
			$table->unsignedInteger('price');
			$table->boolean('is_active')->default(true);
			$table->boolean('is_featured')->default(false);
			$table->unsignedSmallInteger('sort_order')->default(0);
			$table->timestamps();

			$table->index(['is_active', 'sort_order']);
			$table->index('price');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tariffs');
	}
};
