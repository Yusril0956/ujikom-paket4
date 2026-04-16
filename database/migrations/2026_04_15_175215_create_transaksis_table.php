<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            $table->string('booking_code')->nullable()->unique()->comment('Kode verifikasi untuk bukti peminjaman');
            $table->timestamp('pickup_deadline')->nullable()->comment('Batas waktu pengambilan (24 jam dari request)');

            $table->date('borrowed_date')->nullable()->comment('Tanggal buku dipinjam (saat disetujui)');
            $table->date('due_date')->nullable()->comment('Tanggal jatuh tempo pengembalian');
            $table->date('returned_date')->nullable()->comment('Tanggal aktual pengembalian');

            $table->enum('status', ['pending', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak', 'expired'])->default('pending');
            $table->text('notes')->nullable()->comment('Catatan internal admin/kurator');
            $table->text('rejection_reason')->nullable()->comment('Alasan jika ditolak');

            $table->foreignId('verified_by')->nullable()->constrained('users')->comment('Admin yang menyetujui');
            $table->timestamp('verified_at')->nullable();

            $table->integer('fine_amount')->default(0)->comment('Nominal denda dalam rupiah');
            $table->boolean('fine_paid')->default(false);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
            $table->index(['booking_code']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
