<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('billing_type')->default('daily');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
