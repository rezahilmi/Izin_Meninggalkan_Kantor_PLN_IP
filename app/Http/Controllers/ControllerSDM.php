<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Izin;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Imports\UserImport;
use App\Imports\PegawaiImport;
use App\Mail\MailSuratIzin;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\SimpleType\DocProtect;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class ControllerSDM extends Controller
{
  function index()
  {
    $countPribadi = Izin::where('nip',auth()->user()->nip)
                      ->where('keperluan', 0)
                      ->where('status', 1)
                      ->whereYear('tanggal', date('Y'))
                      ->count();
    $countDinas = Izin::where('nip',auth()->user()->nip)
                      ->where('keperluan', 1)
                      ->where('status', 1)
                      ->whereYear('tanggal', date('Y'))
                      ->count();

  $countsIzinPerMonth = [];

    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $countIzin = Pegawai::where('pegawai.nip', auth()->user()->nip)
            ->join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->whereYear('tanggal', date('Y'))
            ->whereMonth('tanggal', $bulan)
            ->where('status', 1)
            ->count();

        $countsIzinPerMonth[$bulan] = $countIzin;
    }
    $disetujui = Izin::where('nip',auth()->user()->nip)
                    ->where('status', 1)
                    ->whereYear('tanggal', date('Y'))
                    ->count();
    $tidakDisetujui = Izin::where('nip',auth()->user()->nip)
                    ->where('status', 2)
                    ->whereYear('tanggal', date('Y'))
                    ->count();

    $countsPerMonth = [];
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $count = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->whereYear('tanggal',date('Y'))
            ->whereMonth('tanggal',$bulan)
            ->count();

    $countsPerMonth[$bulan] = $count;
    }
    $pegawaiHariIni = Pegawai::Join('izin','pegawai.nip','=','izin.nip')
            ->whereDate('tanggal', now()->toDateString())
            ->select('pegawai.*')
            ->distinct('pegawai.nip')
            ->get();

    $jumlahPegawai = Pegawai::where('nip_atasan', auth()->user()->nip)->count();

    $events = array();
    $izin   = Izin::join('pegawai', 'izin.nip', '=', 'pegawai.nip')
                ->get();
    foreach($izin as $izin) {
        $color = null;
        $color = '#' . substr(dechex(crc32($izin->nama)), 0, 6);
        $events[] = [
            'title' => $izin->nama,
            'start' => $izin->tanggal . ' ' . $izin->waktu_keluar,
            'end'   => $izin->tanggal . ' ' . $izin->waktu_kembali,
            'color' => $color
        ];
    }
    $pegawaiIzin = Pegawai::with('izin')
        ->join('users', 'pegawai.nip', '=', 'users.nip')
        ->get();

        $data1 = [];
        $data2 = [];
        foreach ($pegawaiIzin as $pegawai) {
            $data1[$pegawai->nama] = $pegawai->izin->where('keperluan', 0)->where('status', 1)->count();
            $data2[$pegawai->nama] = $pegawai->izin->where('keperluan', 1)->where('status', 1)->count();
        }
        arsort($data1);
        arsort($data2);
    // $pegawaiIzinDinas = Pegawai::whereHas('izin', function ($query) {
    //     $query->where('keperluan', 1);
    // })->with('izin')->get();

    //     $data2 = [];
    //     foreach ($pegawaiIzinDinas as $pegawai) {
    //         $data2[$pegawai->nama] = $pegawai->izin->count();
    //     }

    $belumDisetujui0 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 0)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $belumDisetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 0)
            ->whereMonth('tanggal', now()->month)
            ->count();
    $persenBelumDisetujui = 0;
    if ($belumDisetujui0 > 0) {
        $persenBelumDisetujui = number_format(((($belumDisetujui1 - $belumDisetujui0) / $belumDisetujui0) * 100),2);
    } else {
        // Jika jumlah izin bulan sebelumnya adalah 0, set persentase kenaikan ke 100% atau nilai lainnya sesuai kebutuhan
        $persenBelumDisetujui = 100;
    }
    $disetujui0 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 1)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $disetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 1)
            ->whereMonth('tanggal', now()->month)
            ->count();
    $persenDisetujui = 0;
    if ($disetujui0 > 0) {
        $persenDisetujui = number_format(((($disetujui1 - $disetujui0) / $disetujui0) * 100),2);
    } else {
        // Jika jumlah izin bulan sebelumnya adalah 0, set persentase kenaikan ke 100% atau nilai lainnya sesuai kebutuhan
        $persenDisetujui = 100;
    }
    $tidakDisetujui0 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 2)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $tidakDisetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where('status', 2)
            ->whereMonth('tanggal', now()->month)
            ->count();
    $persenTidakDisetujui = 0;
    if ($tidakDisetujui0 > 0) {
        $persenTidakDisetujui = number_format(((($tidakDisetujui1 - $tidakDisetujui0) / $tidakDisetujui0) * 100), 2);
    } else {
        // Jika jumlah izin bulan sebelumnya adalah 0, set persentase kenaikan ke 100% atau nilai lainnya sesuai kebutuhan
        $persenTidakDisetujui = 100;
    }
    // return dd($pegawai->count());
    return view('atasan/dashboard', compact('tidakDisetujui','disetujui','countsIzinPerMonth','countDinas','countPribadi','jumlahPegawai','events','data1','data2','countsPerMonth','pegawaiHariIni','belumDisetujui1','disetujui1','tidakDisetujui1','persenBelumDisetujui','persenDisetujui','persenTidakDisetujui'));
  }

  function getDaftarAkun()
    {
        $akun = User::join('pegawai as e1', 'users.nip', '=', 'e1.nip')
            ->join('pegawai as e2', 'e1.nip_atasan','=','e2.nip')
            ->where('users.role', '!=', 'keamanan')
            ->select('users.*','e1.*','e1.nama as karyawan','e2.nama as atasan')
            ->get();
        $atasans = User::join('pegawai', 'users.nip', '=','pegawai.nip')
            ->where('role', 'atasan')
            ->select('users.*','pegawai.*')
            ->get();
        $keamanans = User::join('pegawai', 'users.nip', '=','pegawai.nip')
            ->where('role', 'keamanan')
            ->select('users.*','pegawai.*')
            ->get();
        $bidangs = Bidang::all();
        $jabatans = Jabatan::all();
        // return dd($atasan);
        return view('sdm/daftarAkun', compact('akun','atasans','keamanans','bidangs','jabatans'));
    }
  function getImporAkun()
    {

        return view('sdm/imporAkun');
    }
    function imporPegawai(Request $request)
    {
      
      try {
          $request->validate([
              'file' => 'required|mimes:xlsx',
          ]);
          Excel::import(new UserImport, request()->file('file'));
          Excel::import(new PegawaiImport, request()->file('file'));

          Toastr()->positionClass('toast-top-center')->addSuccess('Import data akun pegawai berhasil!');
          return redirect()->back();
      } catch (\Exception $e) {
          Toastr()->positionClass('toast-top-center')->addError('Gagal mengimpor data akun pegawai. Pastikan format file benar dan coba lagi.');
          return redirect()->back();
      }
    }
  function updateAkun(Request $request, $nip)
    {   

      $request->validate([
          'nip'.$nip      => 'numeric',
          'email'.$nip    => [
                                  'required',
                                  'email',
                              ],
          'password'.$nip => 'nullable|min:6',
          'nama'.$nip     => 'string|max:255',
      ], [
          'nip'.$nip.'numeric'       => 'NIP harus berupa angka.',
          'email'.$nip.'required'    => 'Email wajib diisi.',
          'email'.$nip.'email'       => 'Format email tidak valid.',
          'email'.$nip.'unique'      => 'Email sudah digunakan oleh pengguna lain.',
          'password'.$nip.'min'      => 'Password minimal harus 6 karakter.',
          'nama'.$nip.'string'       => 'Nama harus berupa teks.',
          'nama'.$nip.'max'          => 'Nama tidak boleh lebih dari :max karakter.',
      ]);
        try {
            $updateData = [
                'nip'   => $request->input('nip'.$nip),
                'email' => $request->input('email'.$nip),
                'role'  => $request->input('role'.$nip),
            ];

            if ($request->filled('password'.$nip)) {
                $updateData['password'] = Hash::make($request->input('password'.$nip));
            }

            User::where('nip', $nip)->update($updateData);

            Pegawai::where('nip', $nip)->update([
                'nip'        => $request->input('nip'.$nip),
                'nama'       => $request->input('nama'.$nip),
                'nip_atasan' => $request->input('atasan'.$nip),
                'id_bidang' => $request->input('bidang'.$nip),
                'id_jabatan' => $request->input('jabatan'.$nip),
            ]);

            toastr()->positionClass('toast-top-center')->addSuccess('Akun diperbarui!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->positionClass('toast-top-center')->addError('Terjadi kesalahan. Akun tidak dapat diperbarui.'. $e->getMessage());
            return redirect()->back();
        }
    }
  function updateAkunKeamanan(Request $request, $nip)
    {   

      $request->validate([
          'email'.$nip    => [
                                  'required',
                                  'email',
                              ],
          'password'.$nip => 'nullable|min:6',
          'nama'.$nip     => 'string|max:255',
      ], [
          'email'.$nip.'email'       => 'Format email tidak valid.',
          'email'.$nip.'unique'      => 'Email sudah digunakan oleh pengguna lain.',
          'password'.$nip.'min'      => 'Password minimal harus 6 karakter.',
          'nama'.$nip.'string'       => 'Nama harus berupa teks.',
          'nama'.$nip.'max'          => 'Nama tidak boleh lebih dari :max karakter.',
      ]);
        try {
            $updateData = [
                'email' => $request->input('email'.$nip),
            ];

            if ($request->filled('password'.$nip)) {
                $updateData['password'] = Hash::make($request->input('password'.$nip));
            }

            User::where('nip', $nip)->update($updateData);

            Pegawai::where('nip', $nip)->update([
                'nama'       => $request->input('nama'.$nip),
            ]);

            toastr()->positionClass('toast-top-center')->addSuccess('Akun diperbarui!');
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->positionClass('toast-top-center')->addError('Terjadi kesalahan. Akun tidak dapat diperbarui.'. $e->getMessage());
            return redirect()->back();
        }
    }
  function deleteAkun($nip)
    {
      $akun = User::where('nip', $nip)->delete();
      $pegawai = Pegawai::where('nip', $nip)->delete();
      return response()->json(['success' => true, 'message' => 'Akun berhasil dihapus']);
    }
  function getTambahAkun()
    {
        $atasans = User::join('pegawai', 'users.nip', '=','pegawai.nip')
            ->where('role', 'atasan')
            ->select('users.*','pegawai.*')
            ->get();
        $bidangs = Bidang::all();
        $jabatans = Jabatan::all();

        return view('sdm/tambahAkun',compact('atasans','bidangs','jabatans'));
    }
  function tambahAkun(Request $request)
    {
      $request->validate([
          'nip'      => 'required|numeric',
          'email'    => 'required|email|unique:users,email',
          'password' => 'required|min:6',
          'nama'     => 'required|string|max:255',
          'role'     => 'required',
      ], [
          'nip.required'      => 'NIP wajib diisi.',
          'nip.numeric'       => 'NIP harus berupa angka.',
          'email.required'    => 'Email wajib diisi.',
          'email.email'       => 'Format email tidak valid.',
          'email.unique'      => 'Email sudah digunakan oleh pengguna lain.',
          'password.min'      => 'Password minimal harus 6 karakter.',
          'password.required' => 'Password wajib diisi.',
          'nama.required'     => 'Nama wajib diisi.',
          'nama.string'       => 'Nama harus berupa teks.',
          'nama.max'          => 'Nama tidak boleh lebih dari :max karakter.',
          'role.required'     => 'Pilih role.',
      ]);
      try {

        User::create([
            'nip'      => $request->input('nip'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => $request->input('role'),
        ]);

        Pegawai::create([
            'nip'  => $request->input('nip'),
            'nama' => $request->input('nama'),
            'nip_atasan' => $request->input('atasan'),
            'id_bidang' => $request->input('bidang'),
            'id_jabatan' => $request->input('jabatan'),
        ]);

        toastr()->positionClass('toast-top-center')->addSuccess('Akun berhasil ditambahkan!');
        return redirect()->back();
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) { // Duplicate entry error code
            toastr()->positionClass('toast-top-center')->addError('Duplikat NIP ditemukan.');
            return redirect()->back()->withInput($request->input());
        } else {
            toastr()->positionClass('toast-top-center')->addError('Gagal menambahkan akun.');
            return redirect()->back()->withInput($request->input());
        }
    }
  }

}