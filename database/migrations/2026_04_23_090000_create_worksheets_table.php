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
		Schema::create('worksheets', function (Blueprint $table): void {
			$table->id();
			$table->foreignId('topic_id')->constrained()->cascadeOnDelete();
			$table->foreignId('quarter_id')->constrained()->cascadeOnDelete();
			$table->string('title');
			$table->string('seo_title')->nullable();
			$table->text('seo_description')->nullable();
			$table->text('seo_keywords')->nullable();
			$table->text('article')->nullable();
			$table->timestamps();

			$table->index(['topic_id', 'quarter_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('worksheets');
	}
};
