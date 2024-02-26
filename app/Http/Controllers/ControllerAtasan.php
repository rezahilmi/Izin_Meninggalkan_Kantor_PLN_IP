<?php
namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Izin;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Mail\MailSuratIzin;
use App\Mail\mailKeamanan;
use Illuminate\Support\Facades\Mail;
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
use Illuminate\Support\Facades\DB;


class ControllerAtasan extends Controller
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
    $atasan = Pegawai::where('nip',auth()->user()->nip)->first();
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $countIzin = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip');
                if (auth()->user()->role === 'atasan') {
                $countIzin->where('nip_atasan', auth()->user()->nip);
            } else if (auth()->user()->role === 'cuti') {
                $countIzin->where('nip_atasan', $atasan->team_leader);
            }
            $count = $countIzin->whereYear('tanggal',date('Y'))
            ->whereMonth('tanggal',$bulan)
            ->count();

    $countsPerMonth[$bulan] = $count;
    }
    $pegawaiIni = Pegawai::Join('izin','pegawai.nip','=','izin.nip');
                if (auth()->user()->role === 'atasan') {
                $pegawaiIni->where('nip_atasan', auth()->user()->nip);
            } else if (auth()->user()->role === 'cuti') {
                $pegawaiIni->where('nip_atasan', $atasan->team_leader);
            }
            $pegawaiHariIni = $pegawaiIni->whereDate('tanggal', now()->toDateString())
            ->select('pegawai.*')
            ->distinct('pegawai.nip')
            ->get();

    $jumlahPegawai = Pegawai::where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })->count();

    $events = array();
    $izin   = Izin::join('pegawai', 'izin.nip', '=', 'pegawai.nip')
                ->where(function($query) use ($atasan) {
                    if ($atasan->team_leader) {
                        $query->where('nip_atasan', $atasan->team_leader);
                    } else {
                        $query->where('nip_atasan', auth()->user()->nip);
                    }
                })
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
    $pegawaiIzin = Pegawai::where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
        ->with('izin')
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
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
            ->where('status', 0)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $belumDisetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
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
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
            ->where('status', 1)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $disetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
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
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
            ->where('status', 2)
            ->whereMonth('tanggal', now()->subMonth()->month)
            ->count();
    $tidakDisetujui1 = Pegawai::join('izin','pegawai.nip','=','izin.nip')
            ->where(function($query) use ($atasan) {
                if ($atasan->team_leader) {
                    $query->where('nip_atasan', $atasan->team_leader);
                } else {
                    $query->where('nip_atasan', auth()->user()->nip);
                }
            })
            ->where('status', 2)
            ->whereMonth('tanggal', now()->month)
            ->count();
    $persenTidakDisetujui = 0;
    if ($tidakDisetujui0 > 0) {
        $persenTidakDisetujui = number_format(((($tidakDisetujui1 - $tidakDisetujui0) / $tidakDisetujui0) * 100),2);
    } else {
        // Jika jumlah izin bulan sebelumnya adalah 0, set persentase kenaikan ke 100% atau nilai lainnya sesuai kebutuhan
        $persenTidakDisetujui = 100;
    }
    // return dd($pegawai->count());
    return view('atasan/dashboard', compact('tidakDisetujui','disetujui','countsIzinPerMonth','countDinas','countPribadi','jumlahPegawai','events','data1','data2','countsPerMonth','pegawaiHariIni','belumDisetujui1','disetujui1','tidakDisetujui1','persenBelumDisetujui','persenDisetujui','persenTidakDisetujui'));
  }
  function getPersetujuanIzin()
    {
        $izinData = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
            ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->select('pegawai.*','bidang.*','izin.*','jabatan.*')
            ->orderByDesc('izin.created_at');
        if (auth()->user()->role === 'atasan') {
            $izinData->where('izin.nip_penyetuju', auth()->user()->nip);
        }
        $izin = $izinData->get();

      // $tanggal_izin_end = $izin->tanggal . ' ' . $izin->waktu_kembali;

      return view('atasan/persetujuan_izin', compact('izin'));
    }
  function persetujuanIzin(Request $request, $id, $nip)
    {

        $persetujuan = $request->input('persetujuan');
        $izin = Izin::where('id', $id)->firstOrFail();
        $pegawai = Izin::where('izin.id', $izin->id)
          ->join('users', 'izin.nip', '=', 'users.nip')
          ->join('pegawai', 'izin.nip', '=', 'pegawai.nip')
          ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
          ->select('izin.*','pegawai.*','bidang.*','users.*')
          ->first();
        $atasan = Pegawai::where('nip',auth()->user()->nip)
                        ->first();
        // $nama_atasan = Izin::where('id', $id)
        //     ->join('pegawai','izin.nip','=','pegawai.nip')
        //     ->where('nip_penyetuju', 'pegawai.nip')
        //     ->select('pegawai.*')
        //     ->first();

        $tanggal_izin_end = $pegawai->tanggal . ' ' . $pegawai->waktu_kembali;
        $tanggalWaktuKembali = Carbon::parse($tanggal_izin_end, 'Asia/Jakarta');
        $output = now('Asia/Jakarta')->gt($tanggalWaktuKembali);

        if ($persetujuan == '1') {
            $izin->status = 1;
            $izin->save();

            $tanggal_izin = Carbon::createFromFormat('Y-m-d', $pegawai->tanggal)->translatedFormat('d F Y');

            $izinId = $izin->id;


            Lang::setLocale('id');

            $currentDate = Carbon::now()->translatedFormat('d F Y');

            $izin->update(['tgl_disetujui' => now(),'nip_penyetuju' => auth()->user()->nip]);

            $templateProcessor = new TemplateProcessor('surat_izin.docx');

            $keperluanMapping = [
                0 => 'Pribadi',
                1 => 'Dinas',
            ];
            
            $templateProcessor->setValues([
                'nama'                => $pegawai->nama,
                'bidang'              => $pegawai->bidang,
                'nip'                 => $pegawai->nip,
                'tanggal'             => $tanggal_izin,
                'waktu_keluar'        => Carbon::parse($pegawai->waktu_keluar)->format('H:i') . ' WIB',
                'waktu_kembali'       => Carbon::parse($pegawai->waktu_kembali)->format('H:i') . ' WIB',
                'keperluan'           => $keperluanMapping[$pegawai->keperluan],
                'uraian_keperluan'    => $pegawai->uraian_keperluan,
                'nama_atasan'         => $atasan->nama,
                'tanggal_sekarang'    => $currentDate,
            ]);
            
            $folderPath = public_path('surat izin keluar kantor');
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true); // Buat folder jika belum ada
            }

            $filePath = $folderPath . '/surat_izin_' . $izinId . '.docx';
            $izinUpdate = Izin::where('id', $id)
            ->update(['surat_izin' => 'surat_izin_' . $izinId . '.docx']);
            
            $templateProcessor->saveAs($filePath);

            try {
                Mail::to($pegawai->email)->send(new MailSuratIzin($pegawai, $id));

                toastr()->positionClass('toast-top-center')->addSuccess('Berhasil mengirim email ke pegawai!');
            } catch (\Exception $e) {
                toastr()->positionClass('toast-top-center')->addError('Gagal mengirim email: ' . $e->getMessage());
            }

            $keamanan = Pegawai::join('users','pegawai.nip','=','users.nip')
                ->where('role','keamanan')
                ->first();
            try {
                Mail::to($keamanan->email)->send(new mailKeamanan($keamanan, $pegawai, $id));

                toastr()->positionClass('toast-top-center')->addSuccess('Berhasil mengirim email ke keamanan!');
            } catch (\Exception $e) {
                toastr()->positionClass('toast-top-center')->addError('Gagal mengirim email: ' . $e->getMessage());
            }

            toastr()->positionClass('toast-top-center')->addSuccess('Izin telah disetujui!');
            return redirect()->back();
            // return dd($pegawai);
        } elseif ($persetujuan == '2') {
            $izin->status = 2; 
            $izin->save();
            toastr()->positionClass('toast-top-center')->addSuccess('Izin tidak disetujui!');
            return redirect()->back();
        } elseif ($persetujuan == '0' && $output == false) {
            $izin->status = 0; 
            $izin->save();
            $folderPath = public_path('surat izin keluar kantor');
            $filePath = $folderPath . '/surat_izin_' . $izin->id . '.docx';
            if ( file_exists($filePath) ) {
                unlink($filePath);
            }
            $izin->update(['tgl_disetujui' => null,'surat_izin' => null]);
            toastr()->positionClass('toast-top-center')->addError('Izin batal disetujui');
            return redirect()->back();
        } elseif ($persetujuan == '0' && $output == true) {
            toastr()->positionClass('toast-top-center')->addSuccess('Waktu izin telah berakhir, tidak dapat menghapus izin');
            return redirect()->back();
        }
    }
    function getLaporanIzin()
    {
        $atasan = Pegawai::where('nip',auth()->user()->nip)->first();
        $izinData = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
            ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->where('status', 1);
        if (auth()->user()->role === 'atasan') {
            $izinData->where('pegawai.nip_atasan', auth()->user()->nip);
        } else if (auth()->user()->role === 'cuti') {
            $izinData->where('pegawai.nip_atasan', $atasan->team_leader);
        }
        $izin = $izinData->select('pegawai.*','bidang.*','izin.*','jabatan.*')
            ->orderByDesc('izin.created_at')
            ->get();


      return view('atasan/laporan_izin', compact('izin'));
    }
    function filterDate(Request $request)
    {
        $min_date = $request->min;
        $max_date = $request->max;

        
        if (!$max_date && $min_date) {
            $max_date = now()->toDateString();
        }

        $atasan = Pegawai::where('nip', auth()->user()->nip)->first();
        $izinData = Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
            ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
            ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->where('status', 1);

        if (auth()->user()->role === 'atasan') {
            $izinData->where('pegawai.nip_atasan', auth()->user()->nip);
        } else if (auth()->user()->role === 'cuti') {
            $izinData->where('pegawai.nip_atasan', $atasan->team_leader);
        }

        if ($min_date && $max_date) {
            $izinData->whereBetween('izin.tanggal', [$min_date, $max_date]);
        } elseif (!$min_date && $max_date) {
            $izinData->where('izin.tanggal', '<=', $max_date);
        }

        $izin = $izinData->select('pegawai.*', 'bidang.*', 'izin.*', 'jabatan.*')
            ->orderByDesc('izin.created_at')
            ->get(); 

        return view('atasan/laporan_izin', compact('izin'));
        // return response()->json($izin);
        // return dd($min_date);
    }
    function getDaftarPegawai()
    {
        $pegawai = Pegawai::join('users', 'pegawai.nip', '=', 'users.nip')
            ->join('bidang', 'pegawai.id_bidang', '=', 'bidang.id_bidang')
            ->join('jabatan', 'pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->where('pegawai.nip_atasan', auth()->user()->nip)
            ->select('pegawai.*','bidang.*','users.*','jabatan.*')
            ->orderByDesc('users.created_at')
            ->get();


      return view('atasan/daftar_pegawai', compact('pegawai'));
    }
    function pilihTL($nip)
    {
        $user = User::where('nip',$nip)
            ->first();
        $atasan = User::where('users.nip',auth()->user()->nip)
            ->first();
        $teamLeader = Pegawai::where('nip',auth()->user()->nip)
            ->first();
        $pegawai = Pegawai::where('nip_atasan', auth()->user()->nip)->get();
        foreach ($pegawai as $pegawaiItem) {
            $pegawaiItem->nip_atasan = $nip;
            $pegawaiItem->save();
        }

        $cekTeamLeader = Pegawai::where('team_leader', auth()->user()->nip)->select('pegawai.team_leader')->first();
        if ($cekTeamLeader !== null) {
            $update = Pegawai::where('team_leader', auth()->user()->nip)->select('pegawai.team_leader')->update(['team_leader' => $nip]);
        } else {
            $teamLeader->update(['team_leader' => $nip]);
        }
        $user->update([
                        'temporary_role' => $user->role,
                        'role' => 'atasan'
                    ]);
        $atasan->update(['temporary_role' => $atasan->role,'role' => 'cuti']);
    
    toastr()->positionClass('toast-top-center')->addInfo('Anda mengambil cuti');
    return redirect()->back();
    // return dd($cekTeamLeader);
    }
    function selesaiCuti()
    {
        $atasan = User::where('users.nip',auth()->user()->nip)
            ->join('pegawai','users.nip','=','pegawai.nip')
            ->first();
        $user = User::where('nip',$atasan->team_leader)
            ->first();
        $teamLeader = Pegawai::where('nip',auth()->user()->nip)
            ->first();
        $pegawai = Pegawai::where('nip_atasan', $atasan->team_leader)->get();
        foreach ($pegawai as $pegawaiItem) {
            $pegawaiItem->nip_atasan = auth()->user()->nip;
            $pegawaiItem->save();
        }
        if ($atasan->team_leader == null) {
            $atasan->update(['role' => $atasan->temporary_role,'temporary_role' => null]);
        } else {
            $user->update(['role' => $user->temporary_role,'temporary_role' => null]);
            $atasan->update(['role' => $atasan->temporary_role,'temporary_role' => null]);
            $teamLeader->update(['team_leader' => null]);
        }

    //   return dd($atasan);
    return redirect()->back();
    }
}