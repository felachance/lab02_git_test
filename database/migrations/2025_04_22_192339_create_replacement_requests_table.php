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
        Schema::create('replacement_requests', function (Blueprint $table) {
            $table->id();
            $table->string("description")->nullable();
            $table->foreignId("id_replacement_request_type")
                ->constrained("replacement_request_types")
                ->cascadeOnDelete();
            $table->foreignId("id_assignment")
                ->constrained("assignment")
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_requests');
    }
};
