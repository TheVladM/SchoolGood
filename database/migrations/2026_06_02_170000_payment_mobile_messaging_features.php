<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->string('channel')->default('manual')->after('method');
            $table->string('intent_reference')->nullable()->unique()->after('reference');
            $table->string('operator_transaction_id')->nullable()->after('intent_reference');
            $table->string('operator_status')->nullable()->after('operator_transaction_id');
            $table->string('payer_phone', 32)->nullable()->after('operator_status');
            $table->string('receipt_number')->nullable()->unique()->after('payer_phone');
            $table->timestamp('paid_at')->nullable()->after('validated_at');
        });

        Schema::create('payment_webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->string('provider', 32);
            $table->string('event_id')->nullable();
            $table->json('payload');
            $table->string('signature')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('processing_status')->default('received');
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'event_id']);
        });

        Schema::create('announcement_reads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at');
            $table->timestamps();

            $table->unique(['announcement_id', 'user_id']);
        });

        Schema::create('sms_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('to', 32);
            $table->text('message');
            $table->string('driver', 32);
            $table->string('status', 32)->default('sent');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('announcement_reads');
        Schema::dropIfExists('payment_webhook_events');

        Schema::table('payments', function (Blueprint $table): void {
            $table->dropColumn([
                'channel',
                'intent_reference',
                'operator_transaction_id',
                'operator_status',
                'payer_phone',
                'receipt_number',
                'paid_at',
            ]);
        });
    }
};
