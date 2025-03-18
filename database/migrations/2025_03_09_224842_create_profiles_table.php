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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');

            $table->string('full_name', 50);
            $table->string('gender', 6)->nullable();
            $table->string('address_country', 50)->nullable();
            $table->string('address_city', 50)->nullable();
            $table->string('address_street', 50)->nullable();
            $table->string('zip_code', 5)->nullable();
            $table->string('phone_code', 24)->nullable();
            $table->string('phone_number', 24)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
