<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN</title>
    
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="{{ asset('DataTables/dataTables.css') }}" rel="stylesheet" type="text/css">

    
</head>
<body>
    <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

    <main class="content" id="main-content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Status Izin</strong></h1>
            <!-- <div class="info-msg">
            <i class="fa fa-info-circle"></i>
            This is an info message.
            </div> -->
        @if(session('success'))
            <div class="success-msg">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
            </div>
        @endif
            <!-- <div class="warning-msg">
            <i class="fa fa-warning"></i>
            This is a warning message.
            </div> -->
        @if(session('gagal'))
            <div class="error-msg">
            <i data-feather="alert-circle"></i> 
            {{ session('gagal') }}
            </div>
        @endif
        @if($errors->any())
            <div class="error-msg">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
          <div class="card-body">
            <!-- <button type="button" class="btn btn-success"><i class="align-middle me-2" data-feather="plus"></i>Tambah</button> -->
            <table id="myTable" class="table table-hover my-0">
                <thead>
                    <tr>
                        <th class="d-none d-xl-table-cell">Tanggal Izin</th>
                        <th>Waktu Meninggalkan Kantor</th>
                        <th class="d-none d-md-table-cell">Keperluan</th>
                        <th class="d-none d-md-table-cell">Uraian Keperluan</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <th class="d-none d-md-table-cell">Surat Izin</th>
                        <th class="d-none d-md-table-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($izin as $izin)
                    <tr id="deleteForm{{ $izin->id }}">
                        <td class="d-none d-xl-table-cell">{{ \Carbon\Carbon::parse($izin->tanggal)->isoFormat('DD MMMM YYYY') }}</td>
                        <td class="d-none d-xl-table-cell">{{ \Carbon\Carbon::parse($izin->waktu_keluar)->format('H:i') }} - {{ \Carbon\Carbon::parse($izin->waktu_kembali)->format('H:i') }} WIB</td>
                        <td class="d-none d-xl-table-cell">
                            @if($izin->keperluan == 0)
                                <span class="badge rounded-pill bg-secondary">Pribadi</span>
                            @elseif($izin->keperluan == 1)
                                <span class="badge rounded-pill bg-primary">Dinas</span>
                            @endif
                        </td>
                        <td class="d-none d-xl-table-cell w-25">{{ $izin->uraian_keperluan }}</td>
                        <td>
                            @if($izin->status == 0)
                                <span class="badge bg-dark">Belum disetujui</span>
                            @elseif($izin->status == 1)
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($izin->status == 2)
                                <span class="badge bg-danger">Tidak disetujui</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            <a href="{{ !empty($izin->surat_izin) ? asset('surat izin keluar kantor/' . $izin->surat_izin) : '#' }}" class="btn btn-sm {{ !empty($izin->surat_izin) ? 'btn-success' : 'btn-secondary disabled' }}">Unduh</a>
                            <div id="edit{{ $izin->id }}"class="modal">
                                <div class="modal__content">
                                    <h1>Edit Izin</h1>

                                    <form method="POST" action="{{ route('updateIzin', ['id' => $izin->id]) }}" class="row">
                                        @csrf
                                        <div class="col">
                                            <div class="mb-3">
                                            <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                                            <input type="text" id="nama_pegawai" name="nama_pegawai" value="{{ $pegawai->nama }}" class="form-control" readonly>
                                            </div>
                                            <div class="mb-3">
                                            <label for="bidang" class="form-label">Bidang</label>
                                            <input type="text" id="bidang" name="bidang" value="{{ $pegawai->bidang }}" class="form-control" readonly>
                                            </div>
                                            <div class="mb-3" style="width: 150px;">
                                            <label for="tanggal_izin" class="form-label">Diberi Izin Tanggal<span style="color: red;">*</span></label>
                                            <input type="date" id="tanggal_izin" name="tanggal_izin" value="{{ $izin->tanggal }}" class="form-control">
                                            @error('tanggal_izin')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                            </div>
                                            <div class="row mb-3" style="width: 300px;">
                                            <label for="waktu" class="form-label">Waktu Izin Keluar Kantor<span style="color: red;">*</span></label>
                                            <div class="col">
                                                <input type="time" id="waktu_keluar" name="waktu_keluar" value="{{ $izin->waktu_keluar }}" class="form-control">
                                                <label for="waktu_keluar" class="form-label">Keluar</label><br>
                                                @error('waktu_keluar')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                                @enderror
                                            </div>
                                            <i class="w-auto mt-2" data-feather="minus"></i>
                                            <div class="col">
                                                <input type="time" id="waktu_kembali" name="waktu_kembali" value="{{ $izin->waktu_kembali }}" class="form-control">
                                                <label for="waktu_kembali" class="form-label">Kembali</label><br>
                                                @error('waktu_kembali')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                                @enderror
                                            </div>
                                            </div>
                                            <label for="keperluan" class="form-label">Keperluan<span style="color: red;">*</span></label>
                                            <div class="mb-3 row ms-1">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="keperluan" id="pribadi" value="0" {{ $izin->keperluan == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pribadi">
                                                Pribadi
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="keperluan" id="dinas" value="1" {{ $izin->keperluan == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="dinas">
                                                Dinas
                                                </label>
                                            </div>
                                            @error('keperluan')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                            <label for="uraian_keperluan" class="form-label">Uraian Keperluan<span style="color: red;">*</span></label>
                                            <textarea class="form-control" id="uraian_keperluan" name="uraian_keperluan" rows="9">{{ $izin->uraian_keperluan }}</textarea>
                                            @error('uraian_keperluan')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                            </div>
                                            <div class="mb-3 position-relative">
                                            <label for="nama_atasan" class="form-label">Nama Atasan<span style="color: red;">*</span></label><br>
                                            <select class="form-select" id="nama_atasan" name="nama_atasan" aria-label="select example">
                                                <option value=""disabled>Nama atasan</option>
                                                @foreach($cuti as $cuti)
                                                    <option value="{{ $cuti->nip }}" {{ old('nama_atasan') == $cuti->nip ? 'selected' : '' }} disabled>{{ $cuti->nama }} (sedang cuti)</option>
                                                @endforeach
                                                @foreach($atasan as $atasans)
                                                    <option value="{{ $atasans->nip }}" @if($izin['nip_penyetuju'] == $atasans->nip) selected @endif>{{ $atasans->nama }}</option>
                                                @endforeach
                                                @foreach($sdm as $sdms)
                                                    <option value="{{ $sdms->nip }}" @if($izin['nip_penyetuju'] == $sdms->nip) selected @endif>{{ $sdms->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('nama_atasan')
                                                <label><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary m-4 position-absolute bottom-0 end-0">Submit</button>
                                        </div>
                                    </form>
                                    <a href="#" class="modal__close"><h1>&times;</h1></a>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <a href="#edit{{ $izin->id }}" class="btn btn-outline-primary {{ $izin->status == 1 || $izin->status == 2 ? 'disabled' : '' }}"><i data-feather="edit"></i></a>
                                <a href="javascript:void(0)" onclick="deleteIzin('{{ $izin->id }}')" class="btn btn-outline-danger {{ $izin->status == 1 || $izin->status == 2 ? 'disabled' : '' }}"><i data-feather="trash-2"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="8">Tidak ada data izin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
    
    @include('layout.footer')
  </div>
  </div>
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "ordering"  : false,
            "pageLength": 25
        });
    });
    function deleteIzin(id) {
        if (confirm('Anda yakin ingin menghapus izin ini?')) {
            $.ajax({
                type: 'DELETE',
                url: '/deleteIzin/' + id,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": "DELETE"
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, '', {
                            progressBar : true,
                            positionClass: 'toast-top-center',
                            newestOnTop : true
                        });
                        $('#deleteForm' + id).closest('tr').remove();
                    } else {
                        toastr.error(response.message, '', {
                            progressBar : true,
                            positionClass: 'toast-top-center',
                            newestOnTop : true
                        });
                        window.location.reload();
                    }
                },
                error: function(error) {
                    toastr.error('gagal menghapus akun', '', {
                        progressBar : true,
                        positionClass: 'toast-top-center',
                        newestOnTop : true
                    });
                }
            });
        }
    }
    </script>
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="{{ URL::asset('js/charts.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('DataTables/dataTables.js') }}" type="text/javascript" language="javascript"></script>
</body>
</html>
