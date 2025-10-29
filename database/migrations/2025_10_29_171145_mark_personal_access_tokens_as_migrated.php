<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration does nothing except mark that the personal_access_tokens table
        // has been created (which it was by a previous process)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop the personal_access_tokens table
    }
};