<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a deterministic email_hash (HMAC) column so lookups/uniqueness keep
     * working once the email column itself holds encrypted (non-deterministic) ciphertext.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('email_hash', 64)->nullable()->after('email');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->unique('email_hash');
        });

        Schema::table('sponsor_inquiries', function (Blueprint $table) {
            $table->string('email_hash', 64)->nullable()->after('email');
        });
        Schema::table('sponsor_inquiries', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->index('email_hash');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique(['email_hash']);
            $table->dropColumn('email_hash');
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->unique('email');
        });

        Schema::table('sponsor_inquiries', function (Blueprint $table) {
            $table->dropIndex(['email_hash']);
            $table->dropColumn('email_hash');
        });
        Schema::table('sponsor_inquiries', function (Blueprint $table) {
            $table->index('email');
        });
    }
};
