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
        <h1 class="h3 mb-3"><strong>Pilih Team Leader</strong></h1>
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
            <!-- <button type="button" class="btn btn-success"><i class="align-middle me-2" data-feather="plus"></i>Tambah</button> -->
            <table id="myTable" class="table table-hover my-0">
                <thead>
                    <tr class="text-center">
                        <th class="d-none d-md-table-cell">Nama</th>
                        <th class="d-none d-md-table-cell">Jabatan</th>
                        <th class="d-none d-md-table-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $pegawai)
                    <tr id="row">
                        <td class="d-none d-md-table-cell">
                            {{ $pegawai->nama }}
                        </td>
                        <td class="d-none d-md-table-cell">
                            {{ $pegawai->jabatan }}
                        </td>
                        <td class="d-none d-xl-table-cell">
                            <form method="POST" action="{{ route('pilihTL', ['nip' => $pegawai->nip]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">Pilih</button>
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
                "order": [],
                "ordering": false,
            });
        });
    </script>
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="{{ URL::asset('js/charts.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="{{ asset('DataTables/dataTables.js') }}" type="text/javascript" language="javascript"></script>
</body>
</html>
