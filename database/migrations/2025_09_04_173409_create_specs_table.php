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
        //Creates a new table called specs
        Schema::create('specs', function (Blueprint $table) {
            $table->id();//auto-incrementing primary key column named id
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); //is a foreign key referencing the id column in the users table
            $table->string('job');
            $table->unsignedInteger('salary')->nullable();//positive integer, can be null
            $table->text('description')->nullable();
            $table->timestamps();//Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specs');
    }
};
