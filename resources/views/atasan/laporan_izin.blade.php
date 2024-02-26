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
        <h1 class="h3 mb-3"><strong>Laporan Izin</strong></h1>
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
        @if(session('error'))
            <div class="error-msg">
            <i data-feather="alert-circle"></i> 
            {{ session('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form method="get" id="filterDate" action="{{route('filterDate')}}" class="d-flex justify-content-center">
                @csrf
                    <div class="input-group w-75 mb-4">
                        <span class="input-group-text">Minimum date :</span>
                        <input type="date" id="min" name="min" class="form-control" value="{{ request('min') }}">
                        <span class="input-group-text">Maximum date :</span>
                        <input type="date" id="max" name="max" class="form-control" value="{{ request('max') }}">
                        <button class="btn btn-primary">Filter</button>
                    </div>
                </form>
                
            <table id="myTable" class="table table-hover my-0">
                <thead>
                    <tr class="text-center">
                        <th class="d-none d-md-table-cell">Nama</th>
                        <th class="d-none d-md-table-cell">Bidang</th>
                        <th class="d-none d-xl-table-cell">Tanggal Izin</th>
                        <th class="d-none d-md-table-cell">Waktu Meninggalkan Kantor</th>
                        <th class="d-none d-md-table-cell">Keperluan</th>
                        <th class="d-none d-md-table-cell">Uraian Keperluan</th>
                        <th class="d-none d-md-table-cell">
                            @if(auth()->user()->role == 'keamanan')
                                Surat Izin
                            @else
                                Tanggal disetujui
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($izin as $izin)
                    <tr id="row{{ $izin->id }}">
                        <td class="d-none d-md-table-cell">
                            {{ $izin->nama }}
                        </td>
                        <td class="d-none d-md-table-cell">
                            {{ $izin->bidang }}
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
                            @if(auth()->user()->role == 'keamanan')
                                <td class="d-none d-xl-table-cell">
                                    <a href="{{ !empty($izin->surat_izin) ? asset('surat izin keluar kantor/' . $izin->surat_izin) : '#' }}" class="btn btn-sm {{ !empty($izin->surat_izin) ? 'btn-success' : 'btn-secondary disabled' }}">Unduh</a>
                                </td>
                            @else
                                <td class="d-none d-xl-table-cell">{{ $izin->tgl_disetujui }}</td>
                            @endif
                        </th>
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
                "order": [],
                "columnDefs": [
                    { "orderable": false, "targets": [0,1,3,4,5] },
                ],
                dom: '<"html5buttons">Bfgtlip',
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},
                    {extend: 'print'},
                ]
            });
            // $('#filterDate').on('submit', function(e) {
            //     e.preventDefault();
            //     var formData = $(this).serialize();
            //     $.ajax({
            //         url: "{{ route('filterDate') }}",
            //         type: "GET",
            //         data: formData,
            //         dataType: "json",
            //         success: function(response) {
            //             // Bersihkan tabel sebelum menambahkan data baru
            //             $('#myTable tbody').empty();

            //             // Iterasi melalui setiap izin dan tambahkan ke tabel
            //             $.each(response, function(index, izin) {
            //                 var row = '<tr>' +
            //                     '<td class="d-none d-md-table-cell">' + izin.nama + '</td>' +
            //                     '<td class="d-none d-md-table-cell">' + izin.bidang + '</td>' +
            //                     '<td class="d-none d-xl-table-cell">' + izin.tanggal + '</td>' +
            //                     '<td class="d-none d-xl-table-cell">' + izin.waktu_keluar + ' - ' + izin.waktu_kembali + ' WIB</td>' +
            //                     '<td class="d-none d-xl-table-cell">' + (izin.keperluan == 0 ? '<span class="badge rounded-pill bg-secondary">Pribadi</span>' : '<span class="badge rounded-pill bg-primary">Dinas</span>') + '</td>' +
            //                     '<td class="d-none d-xl-table-cell w-25" style="word-break: break-word;">' + izin.uraian_keperluan + '</td>' +
            //                     '<td class="d-none d-xl-table-cell">' + izin.tgl_disetujui + '</td>' +
            //                     '</tr>';
            //                 $('#myTable tbody').append(row);
            //             });
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });
        });
    </script>
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="{{ URL::asset('js/charts.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('DataTables/dataTables.js') }}" type="text/javascript" language="javascript"></script>
</body>
</html>
