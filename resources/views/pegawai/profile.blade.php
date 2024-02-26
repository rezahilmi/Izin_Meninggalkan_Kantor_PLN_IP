<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pegawai</title>

    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body>
    <div class="wrapper">
        @include('layout.sidebar')
        <div class="main">
            @include('layout.navbar')

            <main class="content" id="main-content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3"><strong>Profil</strong></h1>

                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>NIP</th>
                                        <td><b>:</b> {{ $pegawai->nip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td><b>:</b> {{ $pegawai->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bidang</th>
                                        <td><b>:</b> {{ $pegawai->bidang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jabatan</th>
                                        <td><b>:</b> {{ $pegawai->jabatan }}</td>
                                    </tr>
                                    @if($pegawai->nip_atasan == 1)
                                        <tr>
                                            <th>Atasan</th>
                                            <td><b>:</b> Tidak ada atasan</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Atasan</th>
                                            <td><b>:</b> {{ $atasan->nama }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <!-- <div class="d-grid d-md-block">
                                <a href="{{ route('viewEditProfil') }}" class="btn btn-primary" type="button">Edit
                                    Profil <i data-feather="user"></i></a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </main>

            @include('layout.footer')
        </div>
    </div>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('js/charts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#nama_atasan').select2({});
        });
    </script>
</body>

</html>
