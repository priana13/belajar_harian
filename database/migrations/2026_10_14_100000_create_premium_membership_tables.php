<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('jenis_user')->where('nama_jenis', 'Member Premium')->exists()) {
            DB::table('jenis_user')->insert(['nama_jenis' => 'Member Premium', 'created_at' => now(), 'updated_at' => now()]);
        }
        Schema::create('premium_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('duration_months');
            $table->unsignedBigInteger('price');
            $table->text('payment_instructions');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('premium_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('premium_plan_id')->constrained()->restrictOnDelete();
            $table->string('plan_name');
            $table->unsignedInteger('duration_months');
            $table->unsignedBigInteger('price');
            $table->text('payment_instructions');
            $table->string('status')->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'status', 'ends_at']);
        });
        Schema::create('premium_ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi')->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('premium_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->unique()->constrained('materi')->restrictOnDelete();
            $table->string('title');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
        Schema::create('premium_book_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('recipient_name');
            $table->string('phone', 30);
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 10);
            $table->string('status')->default('requested');
            $table->unsignedBigInteger('shipping_fee')->nullable();
            $table->string('shipping_payment_reference')->nullable();
            $table->text('shipping_payment_instructions')->nullable();
            $table->string('courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();
        });
        Schema::create('premium_book_claim_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('premium_book_claim_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('premium_book_id')->constrained()->restrictOnDelete();
            $table->string('book_title');
            $table->timestamps();
            $table->unique(['user_id', 'premium_book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premium_book_claim_items');
        Schema::dropIfExists('premium_book_claims');
        Schema::dropIfExists('premium_books');
        Schema::dropIfExists('premium_ebooks');
        Schema::dropIfExists('premium_subscriptions');
        Schema::dropIfExists('premium_plans');
        // Preserve the user type: accounts may still reference it.
    }
};
