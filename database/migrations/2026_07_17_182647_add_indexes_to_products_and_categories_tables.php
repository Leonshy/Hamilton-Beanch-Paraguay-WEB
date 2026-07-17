<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_featured');
            $table->index(['status', 'order']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['type', 'is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['status', 'order']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['type', 'is_active', 'order']);
        });
    }
};
