<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\ControllerPegawai;
use App\Http\Controllers\ControllerAtasan;
use App\Http\Controllers\ControllerKeamanan;
use App\Http\Controllers\ControllerSDM;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/home', function () {
    return redirect('/admin');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
    Route::get('/register', [SesiController::class, 'registerView']);
    Route::post('/register', [SesiController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [SesiController::class, 'admin']);
    Route::get('/dashboard-pegawai', [ControllerPegawai::class, 'index'])->name('dashboardPegawai')->middleware(['userAkses:pegawai']);
    Route::get('/profile', [ControllerPegawai::class, 'profil'])->name('profile')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::get('/edit-profil', [ControllerPegawai::class, 'viewEditProfil'])->name('viewEditProfil')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::get('/get-jabatan-by-bidang', [ControllerPegawai::class, 'getJabatanByBidang'])->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::post('/update-profil', [ControllerPegawai::class, 'updateProfil'])->name('updateProfil')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::get('/ajukanIzin', [ControllerPegawai::class, 'pengajuanIzin'])->name('pengajuanIzin')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::get('/statusIzin', [ControllerPegawai::class, 'getStatusIzin'])->name('statusIzin')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::get('/surat-izin-pegawai/{filename}', [ControllerPegawai::class, 'show'])->name('surat_izin.show')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::post('/pengajuanIzin', [ControllerPegawai::class, 'storeIzin'])->name('storeIzin')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::delete('/deleteIzin/{id}', [ControllerPegawai::class, 'deleteIzin'])->name('deleteIzin')->middleware(['userAkses:pegawai,atasan,sdm']);
    Route::post('/updateIzin/{id}', [ControllerPegawai::class, 'updateIzin'])->name('updateIzin')->middleware(['userAkses:pegawai,atasan,sdm']);
    
    Route::get('/dashboard-atasan', [ControllerAtasan::class, 'index'])->name('dashboardAtasan')->middleware(['userAkses:atasan,cuti']);
    // Route::get('/sidebar', [ControllerAtasan::class, 'sidebar'])->middleware(['cekProfil','userAkses:atasan']);
    Route::get('/atasan/persetujuanIzin', [ControllerAtasan::class, 'getPersetujuanIzin'])->name('getPersetujuanIzin')->middleware('userAkses:atasan,sdm');
    Route::patch('/atasan/persetujuan/{id}/{nip}', [ControllerAtasan::class, 'persetujuanIzin'])->name('persetujuanIzin')->middleware('userAkses:atasan,sdm');
    Route::get('/atasan/laporanIzin', [ControllerAtasan::class, 'getLaporanIzin'])->name('getLaporanIzin')->middleware('userAkses:atasan,sdm,cuti');
    Route::get('/filter', [ControllerAtasan::class, 'filterDate'])->name('filterDate')->middleware('userAkses:atasan,sdm,cuti,keamanan');
    Route::get('/pemilihan-team-leader', [ControllerAtasan::class, 'getDaftarPegawai'])->name('getDaftarPegawai')->middleware(['userAkses:atasan']);
    Route::post('/pilihTL/{nip}', [ControllerAtasan::class, 'pilihTL'])->name('pilihTL')->middleware('userAkses:atasan');
    Route::post('/selesai-cuti', [ControllerAtasan::class, 'selesaiCuti'])->name('selesaiCuti')->middleware('userAkses:cuti');
    
    Route::get('/dashboard-sdm', [ControllerSDM::class, 'index'])->name('dashboardSDM')->middleware('userAkses:sdm');
    Route::get('/daftarAkun', [ControllerSDM::class, 'getDaftarAkun'])->name('daftarAkun')->middleware('userAkses:sdm');
    Route::delete('/deleteAkun/{nip}', [ControllerSDM::class, 'deleteAkun'])->name('deleteAkun')->middleware('userAkses:sdm');
    Route::post('/updateAkun/{nip}', [ControllerSDM::class, 'updateAkun'])->name('updateAkun')->middleware('userAkses:sdm');
    Route::post('/updateAkunKeamanan/{nip}', [ControllerSDM::class, 'updateAkunKeamanan'])->name('updateAkunKeamanan')->middleware('userAkses:sdm');
    Route::get('/daftarAkun/tambah', [ControllerSDM::class, 'getTambahAkun'])->name('getTambahAkun')->middleware('userAkses:sdm');
    Route::post('/daftarAkun/tambahAkun', [ControllerSDM::class, 'tambahAkun'])->name('tambahAkun')->middleware('userAkses:sdm');
    Route::get('/imporAkun', [ControllerSDM::class, 'getImporAkun'])->name('imporAkun')->middleware('userAkses:sdm');
    Route::post('/imporAkunPegawai', [ControllerSDM::class, 'imporPegawai'])->name('imporPegawai')->middleware('userAkses:sdm');
    
    Route::get('/keamanan/laporanIzin', [ControllerKeamanan::class, 'getLaporanIzin'])->name('getLaporanIzin')->middleware('userAkses:keamanan');
    
    Route::match(['get', 'post'], '/logout', [SesiController::class, 'logout'])->name('logout');
});
