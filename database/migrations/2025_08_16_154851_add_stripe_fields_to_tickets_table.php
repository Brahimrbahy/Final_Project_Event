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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('transaction_id');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null')->after('stripe_session_id');
            $table->enum('payment_status', ['paid', 'unpaid', 'pending', 'refunded'])->default('unpaid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['stripe_session_id', 'payment_id']);
            $table->enum('payment_status', ['paid', 'unpaid', 'refunded'])->default('unpaid')->change();
        });
    }
};
