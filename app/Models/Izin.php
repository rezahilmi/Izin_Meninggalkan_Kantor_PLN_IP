<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    // protected $primaryKey = 'id';
    
    protected $fillable = ['id','nip','nama_pegawai', 'bidang', 'tanggal', 'waktu_keluar', 'waktu_kembali', 'keperluan', 'uraian_keperluan', 'status', 'nip_penyetuju', 'tgl_disetujui','surat_izin'];
}
