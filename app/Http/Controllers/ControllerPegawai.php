<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Izin;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Mail\persetujuanIzin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ControllerPegawai extends Controller
{
  function index()
  {
    $countPribadi = Izin::where('nip',auth()->user()->nip)
                      ->where('keperluan', 0)
                      ->where('status', 1)
                      ->count();
    $countDinas = Izin::where('nip',auth()->user()->nip)
                      ->where('keperluan', 1)
                      ->where('status', 1)
                      ->count();

  $countsPerMonth = [];

    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $countIzin = Pegawai::where('pegawai.nip', auth()->user()->nip)
            ->join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->whereYear('tanggal', date('Y'))
            ->whereMonth('tanggal', $bulan)
            ->where('status', 1)
            ->count();

        $countsPerMonth[$bulan] = $countIzin;
    }
    $disetujui = Izin::where('nip',auth()->user()->nip)
                    ->where('status', 1)
                    ->count();
    $tidakDisetujui = Izin::where('nip',auth()->user()->nip)
                    ->where('status', 2)
                    ->count();
    
    // return dd($countIzin);
    return view('pegawai/dashboard', compact('countPribadi','countDinas','countsPerMonth','disetujui','tidakDisetujui'));
  }
  function profil()
  {
    $pegawai = User::where('users.id', auth()->user()->id)
      ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
      ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
      ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
      ->select('users.*','pegawai.*','bidang.*','jabatan.*')
      ->first();
    $atasan = Pegawai::where('nip', $pegawai->nip_atasan)->first();
    
    return view('pegawai/profile', compact('pegawai','atasan'));
  }
  function viewEditProfil()
  {
    $pegawai = User::where('users.id', auth()->user()->id)
      ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
      ->leftJoin('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
      ->leftJoin('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
      ->select('users.*','pegawai.*','bidang.*','jabatan.*')
      ->first();
      $bidangs = Bidang::all();
      $jabatans = Jabatan::all();

      $atasan = User::where('role', 'atasan')
        ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
        ->get();
      $cuti = User::where('role', 'cuti')
        ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
        ->get();
    
    return view('pegawai/edit_profile', compact('pegawai','atasan','bidangs','jabatans','cuti'));
  }
  function updateProfil(Request $request)
  {
      $request->validate([
          'nip' => 'required|numeric',
          'nama' => 'required|string',
          'bidang' => 'required|string',
          'jabatan' => 'required|string', 
          'nama_atasan' => 'required|string', 
      ], [
          'nip.required' => 'NIP wajib diisi.',
          'nip.numeric' => 'NIP harus berupa angka.',
          'nama.required' => 'Nama wajib diisi.',
          'nama.string' => 'Nama harus berupa teks.',
          'bidang.required' => 'Bidang wajib diisi.',
          'bidang.string' => 'Bidang harus berupa teks.',
          'jabatan.required' => 'Jabatan wajib diisi.',
          'jabatan.string' => 'Jabatan harus berupa teks.',
          'nama_atasan.required' => 'Nama atasan wajib diisi.',
          'nama_atasan.string' => 'Nama atasan harus berupa teks.',
      ]);

      $pegawai = Pegawai::where('pegawai.nip',auth()->user()->nip);
      $user = User::where('users.nip',auth()->user()->nip);

      $pegawai->update([
          'nip' => $request->input('nip'),
          'nama' => $request->input('nama'),
          'id_bidang' => $request->input('bidang'),
          'id_jabatan' => $request->input('jabatan'),
          'nip_atasan' => $request->input('nama_atasan'),
      ]);
      $user->update([
          'nip' => $request->input('nip'),
      ]);

      // Redirect atau tampilkan pesan berhasil update
      return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
  }
  function pengajuanIzin()
  {
    $izin = User::where('users.id', auth()->user()->id)
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
          ->select('users.*','pegawai.*','bidang.*')
          ->first();
    $atasan = User::where('role', 'atasan')
        ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
        ->get();
    $sdm = User::where('role', 'sdm')
        ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
        ->get();
    $cuti = User::where('role', 'cuti')
        ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
        ->get();

    return view('pegawai/ajuan_izin', compact('izin','atasan','sdm','cuti'));
  }
  function getJabatanByBidang(Request $request) {
        $bidangId = $request->bidang;
        $jabatan = Bidang::findOrFail($bidangId)->jabatan;
        return response()->json($jabatan);
    }
  function getStatusIzin()
    {
      $pegawai = User::where('users.id', auth()->user()->id)
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
          ->select('users.*','pegawai.*','bidang.*')
          ->first();
      $izin = User::where('users.id', auth()->user()->id)
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->join('izin', 'pegawai.nip', '=', 'izin.nip')
          ->select('users.*', 'izin.*', 'pegawai.*')
          ->orderByDesc('izin.created_at')
          ->get();
      $atasan = User::where('role', 'atasan')
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->get();
      $sdm = User::where('role', 'sdm')
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->get();
      $cuti = User::where('role', 'cuti')
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->get();

      // $izinId = Izin::where('id', $id)->first();
          
      // $tanggal = Carbon::parse('$izin->tanggal_izin')->format('d F Y');
      // return dd($atasan);
      return view('pegawai/status_izin', compact('izin','pegawai','atasan','sdm','cuti'));
    }

  function storeIzin(Request $request)
    {
        $request->validate([
              'tanggal_izin' => 'required|date|after_or_equal:today',
              'waktu_keluar' => 'required|date_format:H:i',
              'waktu_kembali' => 'required|date_format:H:i|after:waktu_keluar',
              'keperluan' => 'required|string',
              'uraian_keperluan' => 'required|max:255',
              'nama_atasan' => 'required|string|max:255',
          ], [
              'tanggal_izin.required' => 'Tanggal izin harus diisi.',
              'tanggal_izin.date' => 'Format tanggal izin tidak valid.',
              'tanggal_izin.after_or_equal' => 'Tanggal izin harus hari ini atau setelah hari ini.',
              'waktu_keluar.required' => 'Waktu keluar harus diisi.',
              'waktu_keluar.date_format' => 'Format waktu keluar tidak valid.',
              'waktu_kembali.required' => 'Waktu kembali harus diisi.',
              'waktu_kembali.date_format' => 'Format waktu kembali tidak valid.',
              'waktu_kembali.after' => 'Waktu kembali harus setelah waktu keluar.',
              'keperluan.required' => 'Keperluan harus diisi.',
              'uraian_keperluan.required' => 'Uraian keperluan harus diisi.',
              'uraian_keperluan.max' => 'Uraian keperluan tidak boleh melebihi 255 karakter.',
              'nama_atasan.required' => 'Nama atasan harus diisi.',
          ]);
        try {
          $izin = User::where('users.id', auth()->user()->id)
              ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
              ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
              ->select('users.*', 'pegawai.*', 'bidang.*')
              ->first();

          $pegawai = Pegawai::where('nip', auth()->user()->nip)
              ->select('pegawai.*')
              ->first();


          $atasan = Pegawai::where('pegawai.nip', $request->input('nama_atasan'))
              ->join('users', 'pegawai.nip', '=', 'users.nip')
              ->select('users.*', 'pegawai.*')
              ->first();

          $izin = Izin::create([
              'nip' => auth()->user()->nip,
              'tanggal' => $request->input('tanggal_izin'),
              'waktu_keluar' => $request->input('waktu_keluar'),
              'waktu_kembali' => $request->input('waktu_kembali'),
              'keperluan' => $request->input('keperluan'),
              'uraian_keperluan' => $request->input('uraian_keperluan'),
              'nip_penyetuju' => $request->input('nama_atasan'),
          ]);

          try {
                Mail::to($atasan->email)->send(new persetujuanIzin($pegawai, $atasan));

                toastr()->positionClass('toast-top-center')->addSuccess('Berhasil mengirim email!');
            } catch (\Exception $e) {
                toastr()->positionClass('toast-top-center')->addError('Gagal mengirim email: ' . $e->getMessage());
            }

          toastr()->positionClass('toast-top-center')->addSuccess('Izin berhasil dibuat!');
          return redirect()->route('statusIzin');
      } catch (\Exception $e) {
          toastr()->positionClass('toast-top-center')->addError('Terjadi kesalahan. Izin gagal dibuat.');
          return redirect()->back();
      }
    }
  function updateIzin(Request $request, $id)
    {   
        $request->validate([
            'tanggal_izin' => 'required|date|after_or_equal:today',
            'waktu_keluar' => 'required',
            'waktu_kembali' => 'required|after:waktu_keluar',
            'keperluan' => 'required|string',
            'uraian_keperluan' => 'required|string',
            'nama_atasan' => 'required|string|max:255',
        ], [
            'tanggal_izin.required' => 'Tanggal izin harus diisi.',
            'tanggal_izin.date' => 'Format tanggal izin tidak valid.',
            'tanggal_izin.after_or_equal' => 'Tanggal izin harus hari ini atau setelah hari ini.',
            'waktu_keluar.required' => 'Waktu keluar harus diisi.',
            'waktu_kembali.required' => 'Waktu kembali harus diisi.',
            'waktu_kembali.after' => 'Waktu kembali harus setelah waktu keluar.',
            'keperluan.required' => 'Keperluan harus diisi.',
            'uraian_keperluan.required' => 'Uraian keperluan harus diisi.',
            'nama_atasan.required' => 'Nama atasan harus diisi.',
        ]);

        $status = Izin::where('id', $id)->first();
        $izin = User::where('users.id', auth()->user()->id)
          ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
          ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
          ->select('users.*','pegawai.*','bidang.*')
          ->first();

        if ($status->status == 1 || $status->status == 2) {
          toastr()->positionClass('toast-top-center')->addError('Izin telah diperiksa, tidak dapat mengupdate izin');
          return redirect()->route('statusIzin');
        } elseif ($status->status == 0) {
            try {
              Izin::where('id', $id)->update([
                  'nip'               => auth()->user()->nip,
                  'tanggal'           => $request->input('tanggal_izin'),
                  'waktu_keluar'      => $request->input('waktu_keluar'),
                  'waktu_kembali'     => $request->input('waktu_kembali'),
                  'keperluan'         => $request->input('keperluan'),
                  'uraian_keperluan'  => $request->input('uraian_keperluan'),
                  'nip_penyetuju'     => $request->input('nama_atasan'),
              ]);
              toastr()->positionClass('toast-top-center')->addSuccess('Izin diperbarui!');
              return redirect()->route('statusIzin');
          } catch (\Exception $e) {
              toastr()->positionClass('toast-top-center')->addError('Terjadi kesalahan. Izin gagal diperbarui.');
              return redirect()->back();
          }
        }
    }
  function deleteIzin($id)
    {
        // Periksa status izin pengguna
        $status = Izin::where('id', $id)->first();

        // Cek nilai status izin
        // return var_dump($status);
        if ($status->status == 1 || $status->status == 2) {
          return response()->json(['success' => false, 'message' => 'Izin telah diperiksa, tidak dapat menghapus izin']);
        } elseif ($status->status == 0) {
          $izin = Izin::where('id', $id)->delete();
          return response()->json(['success' => true, 'message' => 'Izin berhasil dihapus']);
        }
        
    }
  // public function suratPegawai(Request $request)
  //   {
        
  //       Lang::setLocale('id');

  //       $currentDate = Carbon::now();

  //       $tanggal_sekarang = $currentDate->formatLocalized('%d %B %Y');

  //       $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('surat_izin.docx');

  //       $phpWord->setValues([
  //           'nama'                => $request->input('nama'),
  //           'bidang'              => $request->input('bidang'),
  //           'nip'                 => auth()->user()->nip,
  //           'tanggal'             => $request->input('tanggal_izin'),
  //           'waktu_keluar'        => $request->input('waktu_keluar'),
  //           'waktu_kembali'       => $request->input('waktu_kembali'),
  //           'keperluan'           => $request->input('keperluan'),
  //           'uraian_keperluan'    => $request->input('uraian_keperluan'),
  //           'nama_atasan'         => $request->input('nama_atasan'),
  //           'tanggal_sekarang'    => $tanggal_sekarang,
  //       ]);
  //       // Cek nilai status izin
  //       // return var_dump($status);
  //       if ($status->status == 1 || $status->status == 2) {
  //         return response()->json(['success' => false, 'message' => 'Izin telah diperiksa, tidak dapat menghapus izin']);
  //       } elseif ($status->status == 0) {
  //         $izin = Izin::where('id', $id)->delete();
  //         return response()->json(['success' => true, 'message' => 'Izin berhasil dihapus']);
  //       }
        
  //   }

}