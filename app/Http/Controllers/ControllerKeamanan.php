<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Izin;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Imports\UserImport;
use App\Imports\PegawaiImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;


class ControllerKeamanan extends Controller
{
  function getLaporanIzin()
    {
        $izin = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
            ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->where('status', 1)
            ->whereDate('izin.tanggal', '=', now()->toDateString())
            ->select('pegawai.*','bidang.*','izin.*','jabatan.*')
            ->orderByDesc('izin.created_at')
            ->get();


      return view('atasan/laporan_izin', compact('izin'));
    }

}