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
  <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
  
</head>
<body>
  <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

    <main class="content" id="main-content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong><a href="{{ route('daftarAkun') }}" style="color: inherit; text-decoration: none;">Daftar Akun</a> \ Tambah Akun</strong></h1>
        <div class="card">
          <div class="card-body justify-content-center">
              <form method="POST" action="{{ route('tambahAkun') }}" class="row">
                  @csrf
                  <div class="col">
                      <div class="mb-3">
                          <label for="nip" class="form-label">NIP</label>
                          <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') is-invalid @enderror">
                          @error('nip')
                              <div id="nip" class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                      <div class="mb-3">
                          <label for="nama" class="form-label">Nama</label>
                          <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror">
                          @error('nama')
                              <div id="nama" class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group mb-3">
                          <span class="input-group-text"><i data-feather="mail"></i></span>
                          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                          @error('email')
                              <div id="email" class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                      <label for="password" class="form-label">Password</label>
                      <div class="input-group mb-3">
                          <span class="input-group-text"><i data-feather="lock"></i></span>
                          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                          @error('password')
                              <div id="password" class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                    </div>
                    <div class="col">
                      <div class="mb-3">
                          <label for="role" class="form-label">Role</label>
                          <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" aria-label="Default select example">
                              <option disabled selected>Pilih role</option>
                              <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>pegawai</option>
                              <option value="atasan" {{ old('role') == 'atasan' ? 'selected' : '' }}>atasan</option>
                              <option value="sdm" {{ old('role') == 'sdm' ? 'selected' : '' }}>SDM</option>
                          </select>
                          @error('role')
                              <div id="nama" class="invalid-feedback">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                      <div class="mb-3">
                        <label for="atasan" class="form-label">Atasan</label><br>
                        <select class="form-select" id="atasan" name="atasan" aria-label="Default select example" required>
                        <option disabled selected>Pilih Atasan</option>
                        @foreach($atasans as $atasan)
                            <option value="{{ $atasan->nip }}">{{ $atasan->nama }}</option>
                        @endforeach
                        </select>
                        @error('atasan')
                            <div id="nama" class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="bidang" class="form-label">Bidang</label>
                        <select class="form-select" id="bidang" name="bidang" aria-label="select example">
                            <option value="" disabled selected>Pilih Bidang</option>
                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->id_bidang }}" {{ old('bidang') == 'bidang' ? 'selected' : '' }}>
                                        {{ $bidang->bidang }}
                                    </option>
                                @endforeach
                        </select>
                        @error('bidang')
                            <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select class="form-select" id="jabatan" name="jabatan" aria-label="select example">
                            <option value="" disabled selected>Pilih Bidang</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id_jabatan }}" {{ old('jabatan') == 'jabatan' ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan }}
                                    </option>
                                @endforeach
                        </select>
                        @error('jabatan')
                            <label"><span style="color: red; font-size: 12px;">{{ $message }}</span></label>
                        @enderror
                    </div>
                  </div>
                  <div class="d-flex justify-content-center">
                      <button type="submit" class="btn btn-primary w-auto">Submit</button>
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
            $('#atasan').select2({});
            $('#jabatan').select2({});
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html>
