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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('contact_phone')->nullable()->after('company_name');
            $table->text('company_bio')->nullable()->after('contact_phone');
            $table->string('website')->nullable()->after('company_bio');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'contact_phone', 'company_bio', 'website', 'status']);
        });
    }
};
