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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('media_id');
            $table->string('cta2_text')->nullable()->after('cta_url');
            $table->string('cta2_url')->nullable()->after('cta2_text');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'cta2_text', 'cta2_url']);
        });
    }
};
