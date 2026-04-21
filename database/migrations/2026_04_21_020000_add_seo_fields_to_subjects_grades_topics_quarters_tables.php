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
		Schema::table('subjects', function (Blueprint $table): void {
			$table->string('seo_title')->nullable()->after('position');
			$table->text('seo_description')->nullable()->after('seo_title');
			$table->text('seo_keywords')->nullable()->after('seo_description');
			$table->text('article')->nullable()->after('seo_keywords');
		});

		Schema::table('grades', function (Blueprint $table): void {
			$table->string('seo_title')->nullable()->after('slug');
			$table->text('seo_description')->nullable()->after('seo_title');
			$table->text('seo_keywords')->nullable()->after('seo_description');
			$table->text('article')->nullable()->after('seo_keywords');
		});

		Schema::table('topics', function (Blueprint $table): void {
			$table->string('seo_title')->nullable()->after('position');
			$table->text('seo_description')->nullable()->after('seo_title');
			$table->text('seo_keywords')->nullable()->after('seo_description');
			$table->text('article')->nullable()->after('seo_keywords');
		});

		Schema::table('quarters', function (Blueprint $table): void {
			$table->string('seo_title')->nullable()->after('number');
			$table->text('seo_description')->nullable()->after('seo_title');
			$table->text('seo_keywords')->nullable()->after('seo_description');
			$table->text('article')->nullable()->after('seo_keywords');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('subjects', function (Blueprint $table): void {
			$table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'article']);
		});

		Schema::table('grades', function (Blueprint $table): void {
			$table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'article']);
		});

		Schema::table('topics', function (Blueprint $table): void {
			$table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'article']);
		});

		Schema::table('quarters', function (Blueprint $table): void {
			$table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'article']);
		});
	}
};
