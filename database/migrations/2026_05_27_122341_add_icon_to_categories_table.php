<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_type')->default('icon')->after('is_active'); // 'icon' | 'image'
            $table->text('icon')->nullable()->after('icon_type');              // SVG path d=""
            $table->foreignId('media_id')->nullable()->after('icon')
                  ->constrained('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn(['icon_type', 'icon', 'media_id']);
        });
    }
};
