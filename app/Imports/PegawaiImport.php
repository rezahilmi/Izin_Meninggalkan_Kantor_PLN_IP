<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PegawaiImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pegawai([
            'nip'      => $row[0],
            'nama'     => $row[1],
            'nip_atasan' => $row[5],
            'id_bidang' => $row[6],
            'id_jabatan' => $row[7],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
