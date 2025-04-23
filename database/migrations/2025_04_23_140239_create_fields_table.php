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
        // Antes de criar, garanta que a tabela não existe (boa prática em ambiente de desenvolvimento)
        if (!Schema::hasTable('fields')) {
            Schema::create('fields', function (Blueprint $table) {
                $table->id(); // Cria campo "id" como BIGINT UNSIGNED AUTO_INCREMENT
                $table->unsignedBigInteger('form_id'); // Compatível com id() ou bigIncrements()
                $table->string('attribute');
                $table->text('question')->nullable();
                $table->json('options')->nullable();
                $table->boolean('required')->default(false);
                $table->boolean('filled')->default(false);
                $table->timestamps();

                // Foreign key correta e compatível
                $table->foreign('form_id')
                      ->references('id')
                      ->on('forms')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
