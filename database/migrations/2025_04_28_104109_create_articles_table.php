<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->text('body');
            $table->string('image')->nullable();
            
            // Definisci la relazione con l'utente
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Definisci la relazione con la categoria
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
            
            // Nuovo campo per lo stato di accettazione
            $table->boolean('is_accepted')
                  ->nullable()  // Può essere null (in attesa di revisione)
                  ->default(null);
            
            // Nuovo campo per lo slug
            $table->string('slug')->unique();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
