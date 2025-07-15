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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('type')->comment('Type of resource, e.g., food, wood, rock');
            $table->integer('quantity')->default(0)->comment('Quantity of the resource');
            $table->string('status')->default('available')->comment('Status of the resource, e.g., available, gathering, exhausted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
