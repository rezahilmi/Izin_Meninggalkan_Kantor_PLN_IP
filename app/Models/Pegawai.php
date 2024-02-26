<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'nip';
    
    protected $fillable = ['nip','nama','team_leader', 'nip_atasan', 'id_bidang', 'id_jabatan'];

    public function izin()
    {
        return $this->hasMany(Izin::class, 'nip', 'nip');
    }
}
