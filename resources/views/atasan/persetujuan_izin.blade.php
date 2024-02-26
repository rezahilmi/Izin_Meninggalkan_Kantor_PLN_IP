<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN</title>
    
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="{{ asset('DataTables/dataTables.css') }}" rel="stylesheet" type="text/css">

    <style>
        #approve {
        display: none;
        }
    </style>
    
</head>
<body>
    <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

    <main class="content" id="main-content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Persetujuan Izin</strong></h1>
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
        <div class="card">
          <div class="card-body">
            <!-- <button type="button" class="btn btn-success"><i class="align-middle me-2" data-feather="plus"></i>Tambah</button> -->
            <table id="myTable" class="table table-hover my-0">
                <thead>
                    <tr class="text-center">
                        <th class="d-none d-md-table-cell">Nama</th>
                        <th class="d-none d-md-table-cell">Jabatan</th>
                        <th class="d-none d-xl-table-cell">Tanggal Izin</th>
                        <th class="d-none d-md-table-cell">Waktu Meninggalkan Kantor</th>
                        <th class="d-none d-md-table-cell">Keperluan</th>
                        <th class="d-none d-md-table-cell">Uraian Keperluan</th>
                        <th class="d-none d-md-table-cell text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($izin as $izin)
                    <tr id="row{{ $izin->id }}">
                        <td class="d-none d-md-table-cell">
                            {{ $izin->nama }}
                        </td>
                        <td class="d-none d-md-table-cell">
                            {{ $izin->jabatan }}
                        </td>
                        <td class="d-none d-xl-table-cell">{{ \Carbon\Carbon::parse($izin->tanggal)->isoFormat('DD MMMM YYYY') }}</td>
                        <td class="d-none d-xl-table-cell">{{ \Carbon\Carbon::parse($izin->waktu_keluar)->format('H:i') }} - {{ \Carbon\Carbon::parse($izin->waktu_kembali)->format('H:i') }} WIB</td>
                        <td class="d-none d-xl-table-cell">
                            @if($izin->keperluan == 0)
                                <span class="badge rounded-pill bg-secondary">Pribadi</span>
                            @elseif($izin->keperluan == 1)
                                <span class="badge rounded-pill bg-primary">Dinas</span>
                            @endif
                        </td>
                        <td class="d-none d-xl-table-cell w-25" style="word-break: break-word;">{{ $izin->uraian_keperluan }}</td>
                        <td class="text-center">
                            <form class="approval-form" action="{{ route('persetujuanIzin', ['id' => $izin->id, 'nip' => $izin->nip]) }}" method="post">
                                @csrf
                                @method('patch')
                                @if($izin->status == 0)
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <button type="submit" id="approveBtn{{ $izin->id }}" name="persetujuan" value="1" class="btn btn-outline-success approve-btn" data-status="1">
                                        <i data-feather="check"></i>
                                    </button>
                                    <button type="submit" id="rejectBtn{{ $izin->id }}" name="persetujuan" value="2" class="btn btn-outline-danger reject-btn" data-status="2">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                @elseif($izin->status == 1 || $izin->status == 2)
                                @php
                                    $tanggal_izin_end = $izin->tanggal . ' ' . $izin->waktu_kembali;
                                    $tanggalWaktuKembali = \Carbon\Carbon::parse($tanggal_izin_end, 'Asia/Jakarta');
                                    $output = now('Asia/Jakarta')->gt($tanggalWaktuKembali);
                                @endphp
                                <button type="submit" id="deleteBtn{{ $izin->id }}" name="persetujuan" value="0" class="btn btn-outline-danger cancel-btn" data-status="0" @if($output) disabled @endif><i data-feather="delete"></i></button><br>
                                    @if($izin->status == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($izin->status == 2)
                                        <span class="badge bg-danger">Tidak disetujui</span>
                                    @endif
                                @endif
                            </form>
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
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 25,
                "ordering": false,
            });
            // function persetujuanIzin(id) {
            //     if (confirm('Anda yakin ingin menghapus izin ini?')) {
            //         $.ajax({
            //             type: 'PUT',
            //             url: '/persetujuanIzin/' + id,
            //             data: {
            //                 "_token": "{{ csrf_token() }}",
            //                 "_method": "PUT"
            //             },
            //             success: function(response) {
            //                 if (response.status == 1) {
            //                     $('#approveBtn' + id).hide();
            //                     $('#rejectBtn' + id).hide();
            //                     $('#deleteBtn' + id).show();
            //                 } else if (response.status == 2) {
            //                     $('#approveBtn' + id).show();
            //                     $('#rejectBtn' + id).show();
            //                     $('#deleteBtn' + id).hide();
            //                 } else if (response.status == 0) {
            //                     $('#approveBtn' + id).show();
            //                     $('#rejectBtn' + id).show();
            //                     $('#deleteBtn' + id).hide();
            //                 }
            //             },
            //             error: function(error) {
            //                 console.log('Error:', error);
            //                 alert('Gagal menghapus izin.');
            //             }
            //         });
            //     }
            // }
        });
    </script>
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('DataTables/dataTables.js') }}" type="text/javascript" language="javascript"></script>
</body>
</html>
