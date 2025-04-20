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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->unsignedBigInteger('problem_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('client')->nullable();
            $table->string('issue');
            $table->text('description');
            $table->enum('status', ['open', 'onprogress', 'resolved', 'revition'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('priority', ['low', 'middle', 'high']);
            $table->string('via')->default('web');
            $table->timestamps();

            // foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('set null');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
