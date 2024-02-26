<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN</title>

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
                    <h1 class="h3 mb-3"><strong>Pengajuan Izin Keluar Kantor</strong></h1>

                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('storeIzin') }}" class="row">
                                @csrf
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                                        <input type="text" id="nama_pegawai" name="nama_pegawai"
                                            value="{{ $izin->nama }}" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bidang" class="form-label">Bidang</label>
                                        <input type="text" id="bidang" name="bidang" value="{{ $izin->bidang }}"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="mb-3" style="width: 150px;">
                                        <label for="tanggal_izin" class="form-label">Diberi Izin Tanggal<span
                                                style="color: red;">*</span></label>
                                        <input type="date" id="tanggal_izin" name="tanggal_izin"
                                            value="{{ old('tanggal_izin') }}" class="form-control" min="{{ date('Y-m-d') }}">
                                        @error('tanggal_izin')
                                            <label"><span
                                                    style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                    </div>
                                    <div class="row mb-3" style="width: 300px;">
                                        <label for="waktu" class="form-label">Waktu Izin Keluar Kantor<span
                                                style="color: red;">*</span></label>
                                        <div class="col">
                                            <input type="time" id="waktu_keluar" name="waktu_keluar"
                                                value="{{ old('waktu_keluar') }}" class="form-control" min="07:00" max="16:00">
                                            <label for="waktu_keluar" class="form-label">Keluar</label><br>
                                            @error('waktu_keluar')
                                                <label><span
                                                        style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                                @enderror
                                        </div>
                                        <i class="w-auto mt-2" data-feather="minus"></i>
                                        <div class="col">
                                            <input type="time" id="waktu_kembali" name="waktu_kembali"
                                                value="{{ old('waktu_kembali') }}" class="form-control" min="07:00" max="16:00">
                                            <label for="waktu_kembali" class="form-label">Kembali</label><br>
                                            @error('waktu_kembali')
                                                <label"><span
                                                        style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                                @enderror
                                        </div>
                                    </div>
                                    <label for="keperluan" class="form-label">Keperluan<span
                                            style="color: red;">*</span></label>
                                    <div class="mb-3 row ms-1">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="keperluan"id="pribadi" value="0"{{ old('keperluan') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pribadi">
                                                Pribadi
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="keperluan"id="dinas" value="1"{{ old('keperluan') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dinas">
                                                Dinas
                                            </label>
                                        </div>
                                        @error('keperluan')
                                            <label"><span
                                                    style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="uraian_keperluan" class="form-label">Uraian Keperluan<span
                                                style="color: red;">*</span></label>
                                        <textarea class="form-control" id="uraian_keperluan" name="uraian_keperluan" rows="9">{{ old('uraian_keperluan') }}</textarea>
                                        @error('uraian_keperluan')
                                            <label"><span
                                                    style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                    </div>
                                    <div class="mb-3 position-relative">
                                        <label for="nama_atasan" class="form-label">Nama Atasan<span style="color: red;">*</span></label><br>
                                        <select class="form-select" id="nama_atasan" name="nama_atasan" aria-label="select example">
                                            <option value="" selected disabled>Nama atasan</option>
                                            @foreach($cuti as $cuti)
                                                <option value="{{ $cuti->nip }}" {{ old('nama_atasan') == $cuti->nip ? 'selected' : '' }} disabled>{{ $cuti->nama }} (sedang cuti)</option>
                                            @endforeach
                                            @foreach($atasan as $atasan)
                                                <option value="{{ $atasan->nip }}" {{ old('nama_atasan') == $atasan->nip ? 'selected' : '' }}>{{ $atasan->nama }}</option>
                                            @endforeach
                                            @foreach($sdm as $sdm)
                                                <option value="{{ $sdm->nip }}" {{ old('nama_atasan') == $sdm->nip ? 'selected' : '' }}>{{ $sdm->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('nama_atasan')
                                            <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                                            @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary m-4 position-absolute bottom-0 end-0">Submit</button>
                                </div>
                            </form>
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
