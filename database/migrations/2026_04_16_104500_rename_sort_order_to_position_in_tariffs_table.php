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
		Schema::table('tariffs', function (Blueprint $table) {
			$table->dropIndex(['is_active', 'sort_order']);
		});

		Schema::table('tariffs', function (Blueprint $table) {
			$table->renameColumn('sort_order', 'position');
		});

		Schema::table('tariffs', function (Blueprint $table) {
			$table->index(['is_active', 'position']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('tariffs', function (Blueprint $table) {
			$table->dropIndex(['is_active', 'position']);
		});

		Schema::table('tariffs', function (Blueprint $table) {
			$table->renameColumn('position', 'sort_order');
		});

		Schema::table('tariffs', function (Blueprint $table) {
			$table->index(['is_active', 'sort_order']);
		});
	}
};
