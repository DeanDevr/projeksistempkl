<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnggotaPengajuan extends Model
{
    use HasFactory;

    protected $fillable = ['pengajuan_id', 'nama', 'nisn', 'asal_sekolah', 'jurusan'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}