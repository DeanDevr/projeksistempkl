<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

  protected $fillable = [
   'user_id',
   'nama',
   'asal_sekolah',      
   'jurusan',
   'tanggal_mulai',    
   'tanggal_selesai',   
   'surat_pengantar',
   'nisn',
   'status',
   'jenis_pengajuan',
];
      protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function anggota()
{
    return $this->hasMany(AnggotaPengajuan::class);
}
}