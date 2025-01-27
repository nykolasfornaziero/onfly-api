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
        Schema::create('travel_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('destination',255);
            $table->dateTime('departure_date');
            $table->dateTime('return_date')->nullable();
            $table->enum('status',['solicitado','aprovado','cancelado'])->default('solicitado');

            $table->foreign('user_id')->references('id')->on('users'); // Adding the foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_destinations');
    }
};
