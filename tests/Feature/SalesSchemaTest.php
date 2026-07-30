<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('sales tables and relationship columns exist', function () {
    expect(Schema::hasTable('transactions'))->toBeTrue()
        ->and(Schema::hasTable('transaction_items'))->toBeTrue()
        ->and(Schema::hasColumn('products', 'category_id'))->toBeTrue()
        ->and(Schema::hasColumn('transaction_items', 'transaction_id'))->toBeTrue()
        ->and(Schema::hasColumn('transaction_items', 'product_id'))->toBeTrue();
});
