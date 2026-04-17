<?php

use App\Support\CopyrightYearRange;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class);

test('до года старта возвращает один календарный год', function (): void {
	expect(CopyrightYearRange::format(Carbon::parse('2025-06-01')))->toBe('2025');
});

test('в год старта возвращает только год старта', function (): void {
	expect(CopyrightYearRange::format(Carbon::parse('2026-01-01')))->toBe('2026');
});

test('после года старта возвращает диапазон с тире', function (): void {
	expect(CopyrightYearRange::format(Carbon::parse('2028-12-31')))->toBe("2026\u{2013}2028");
});
