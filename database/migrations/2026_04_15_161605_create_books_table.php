<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->string('issn')->nullable();

            $table->string('category');
            $table->string('classification')->nullable()->comment('DDC/LCC code');
            $table->string('location')->nullable()->comment('Rak/Gudang');
            $table->enum('availability_status', ['tersedia', 'dipinjam', 'reservasi', 'arsip', 'perbaikan'])->default('tersedia');

            $table->string('cover_image')->nullable();
            $table->text('synopsis')->nullable();
            $table->text('curator_notes')->nullable();

            $table->integer('stock_total')->default(1);
            $table->integer('stock_available')->default(1);
            $table->boolean('is_public')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['title', 'author', 'isbn']);
            $table->index(['category', 'availability_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
