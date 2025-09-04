<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anggota_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('nisn')->nullable();
            $table->string('asal_sekolah');
            $table->string('jurusan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_pengajuans');
    }
};