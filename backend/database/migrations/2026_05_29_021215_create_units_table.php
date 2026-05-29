<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->string('status')->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['beach_club_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
