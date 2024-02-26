<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = 'bidang';
    protected $primaryKey = 'id_bidang';
    
    protected $fillable = ['id_bidang','bidang'];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'id_bidang', 'id_bidang');
    }
}
