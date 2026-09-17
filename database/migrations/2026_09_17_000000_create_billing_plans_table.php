<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index('name');
        });

        Schema::create('billing_plan_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_plan_id')
                ->constrained('billing_plans')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('source_product_id');
            $table->unsignedBigInteger('target_product_id');
            $table->unsignedBigInteger('source_campaign_id');
            $table->unsignedBigInteger('target_campaign_id');
            $table->unsignedInteger('days_to_next_billing');
            $table->string('target_mid', 100);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['source_product_id', 'source_campaign_id']);
            $table->index(['target_product_id', 'target_campaign_id']);
            $table->index('target_mid');
            $table->index(['billing_plan_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_plan_rules');
        Schema::dropIfExists('billing_plans');
    }
};
