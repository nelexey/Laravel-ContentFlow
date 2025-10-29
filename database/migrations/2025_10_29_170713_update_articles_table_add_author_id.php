<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Add the author_id column as nullable first
            $table->foreignId('author_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->after('body');
        });
        
        // Set a default author for existing articles (if any users exist)
        $firstUserId = DB::table('users')->first()?->id;
        if ($firstUserId) {
            DB::table('articles')->update(['author_id' => $firstUserId]);
        }
        
        // Make the column non-nullable
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });
    }
};