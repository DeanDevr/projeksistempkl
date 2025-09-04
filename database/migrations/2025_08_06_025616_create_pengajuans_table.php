<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_pengajuan')->nullable();
            $table->string('nama');
            $table->string('nisn');
            $table->string('asal_sekolah');
            $table->string('jurusan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('surat_pengantar')->nullable();
            $table->string('status')->default('diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};