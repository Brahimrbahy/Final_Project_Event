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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_intent_id');
            $table->decimal('total_amount', 10, 2)->nullable()->after('organizer_amount');
            $table->string('payment_method')->nullable()->after('total_amount');
            $table->timestamp('processed_at')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['stripe_session_id', 'total_amount', 'payment_method', 'processed_at']);
        });
    }
};
