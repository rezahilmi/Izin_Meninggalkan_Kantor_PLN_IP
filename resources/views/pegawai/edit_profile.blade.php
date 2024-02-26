<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN</title>

    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>


</head>

<body>
    <div class="wrapper">
        @include('layout.sidebar')
        <div class="main">
            @include('layout.navbar')

            <main class="content" id="main-content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3"><strong>Edit Profil</strong></h1>
                    
                    @if(session('error'))
                        <div class="error-msg">
                        <i data-feather="alert-circle"></i> 
                        {{ session('error') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('updateProfil') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="nip" class="form-label">NIP</label>
                                            <input type="number" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="form-control">
                                            @error('nip')
                                                <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" class="form-control">
                                            @error('nama')
                                                <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="bidang" class="form-label">Bidang</label>
                                            <select class="form-select" id="bidang" name="bidang" aria-label="select example">
                                                <option value="" disabled {{ is_null($pegawai->id_bidang) ? 'selected' : '' }}>Pilih Bidang</option>
                                                    @foreach ($bidangs as $bidang)
                                                        <option value="{{ $bidang->id_bidang }}" {{ old('bidang', $pegawai->id_bidang) == $bidang->id_bidang ? 'selected' : '' }}>
                                                            {{ $bidang->bidang }}
                                                        </option>
                                                    @endforeach
                                            </select>
                                            @error('bidang')
                                                <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="jabatan" class="form-label">Jabatan</label>
                                            <select class="form-select" id="jabatan" name="jabatan" aria-label="select example">
                                                <option value="" disabled {{ is_null($pegawai->id_jabatan) ? 'selected' : '' }}>Pilih Bidang</option>
                                                    @foreach ($jabatans as $jabatan)
                                                        <option value="{{ $jabatan->id_jabatan }}" {{ old('jabatan', $pegawai->id_jabatan) == $jabatan->id_jabatan ? 'selected' : '' }}>
                                                            {{ $jabatan->jabatan }}
                                                        </option>
                                                    @endforeach
                                            </select>
                                            @error('jabatan')
                                                <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_atasan" class="form-label">Atasan</label><br>
                                            <select class="form-select" id="nama_atasan" name="nama_atasan" aria-label="select example">
                                                <option value="" disabled {{ is_null($pegawai->nip_atasan) ? 'selected' : '' }}>Pilih nama atasan</option>
                                                @foreach($atasan as $atasan)
                                                    <option value="{{ $atasan->nip }}" @if($pegawai['nip_atasan'] == $atasan->nip) selected @endif>{{ $atasan->nama }}</option>
                                                @endforeach
                                                @foreach($cuti as $cuti)
                                                    <option value="{{ $cuti->nip }}" @if($pegawai['nip_atasan'] == $cuti->nip) selected @endif>{{ $cuti->nama }}</option>
                                                @endforeach
                                                <option value="1">tidak ada atasan</option>
                                            </select>
                                            @error('nama_atasan')
                                                <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                        </div>
                                </div>
                            </div>
                            <div class="d-grid d-md-block mt-3">
                                <button class="btn btn-success" type="submit">Edit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            @include('layout.footer')
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#bidang').on('change', function() {
                var bidangId = $(this).val();
                if (bidangId) {
                    $.ajax({
                        url: '/get-jabatan-by-bidang',
                        type: 'GET',
                        data: {bidang: bidangId},
                        dataType: 'json',
                        success: function(data) {
                            $('#jabatan').empty();
                            $.each(data, function(key, value) {
                                $('#jabatan').append('<option value="' + value.id_jabatan + '">' + value.jabatan + '</option>');
                            });
                        }
                    });
                } else {
                    $('#jabatan').empty();
                }
            });
        });
    </script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('js/charts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        // $(document).ready(function() {
        //     $('#nama_atasan').select2({});
        //     $('#jabatan').select2({});
        // });
    </script>
</body>

</html>
