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
        Schema::table('lendings', function (Blueprint $table) {
            $table->longText('return_sign')->nullable()->after('borrow_sign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lendings', function (Blueprint $table) {
            $table->dropColumn('return_sign');
        });
    }
};
