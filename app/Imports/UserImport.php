<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'nip'      => $row[0],
            'email'    => $row[2], 
            'password' => Hash::make($row[3]),
            'role'     => $row[4],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
