<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PLN</title>
  
  <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="{{ asset('DataTables/dataTables.css') }}" rel="stylesheet" type="text/css">
  
</head>
<body>
  <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

    <main class="content" id="main-content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Akun Bagian Keamanan</strong></h1>
        <div class="card">
          <div class="card-body">
            <table id="" class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-md-table-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keamanans as $keamanan)
                    <tr id="deleteForm{{ $keamanan->nip }}">
                        <td class="d-none d-xl-table-cell">{{ $keamanan->nama }}</td>
                        <td class="d-none d-xl-table-cell">{{ $keamanan->email }}</td>
                        <td class="d-none d-md-table-cell">
                            <div id="edit1{{ $keamanan->nip }}"class="modal">
                                <div class="modal__content w-50">
                                    <h1>Edit Akun</h1>
                                    <form method="POST" action="{{ route('updateAkunKeamanan', ['nip' => $keamanan->nip]) }}" class="row">
                                        @csrf
                                        <div class="mb-3">
                                          <label for="nama" class="form-label">Nama</label>
                                          <input type="text" name="nama{{ $keamanan->nip }}" value="{{ $keamanan->nama }}" class="form-control @error('nama'.$keamanan->nip) is-invalid @enderror" required>
                                          @error('nama'.$keamanan->nip)
                                          <div id="nama" class="invalid-feedback">
                                            {{ $message }}
                                          </div>
                                          @enderror
                                        </div>
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group mb-3">
                                          <span class="input-group-text"><i data-feather="mail"></i></span>
                                          <input type="email" name="email{{ $keamanan->nip }}" value="{{ $keamanan->email }}" class="form-control @error('email'.$keamanan->nip) is-invalid @enderror" required>
                                          @error('email'.$keamanan->nip)
                                              <div id="email" class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                          @enderror
                                        </div>
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group mb-3">
                                          <span class="input-group-text"><i data-feather="lock"></i></span>
                                          <input type="password" name="password{{ $keamanan->nip }}" class="form-control @error('password'.$keamanan->nip) is-invalid @enderror">
                                          @error('password'.$keamanan->nip)
                                              <div id="password" class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                          @enderror
                                        </div>
                                        <div class="d-flex justify-content-center">
                                          <button type="submit" class="btn btn-primary w-auto">Submit</button>
                                        </div>
                                    </form>
                                    <a href="#" class="modal__close"><h1>&times;</h1></a>
                                </div>
                            </div>
                                <a href="#edit1{{ $keamanan->nip }}" class="btn btn-outline-primary"><i data-feather="edit"></i></a>
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

      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Daftar Akun</strong></h1>
        <div class="card">
          <div class="card-body">
            <a class="btn btn-dark mb-3" href="{{ route('getTambahAkun') }}" role="button"><i data-feather="plus"></i> Tambah Akun</a>
            <table id="myTable" class="table table-hover my-0">
                <thead>
                    <tr>
                        <th class="d-none d-xl-table-cell">NIP</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-md-table-cell">Role</th>
                        <th class="d-none d-md-table-cell">Atasan</th>
                        <th class="d-none d-md-table-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($akun as $akun)
                    <tr id="deleteForm{{ $akun->nip }}">
                        <td class="d-none d-xl-table-cell">{{ $akun->nip }}</td>
                        <td class="d-none d-xl-table-cell">{{ $akun->karyawan }}</td>
                        <td class="d-none d-xl-table-cell">{{ $akun->email }}</td>
                        <td class="d-none d-xl-table-cell">{{ $akun->role }}</td>
                        <td class="d-none d-xl-table-cell">{{ $akun->atasan }}</td>
                        <td class="d-none d-md-table-cell">
                            <div id="edit{{ $akun->nip }}"class="modal">
                                <div class="modal__content w-50">
                                    <h1>Edit Akun</h1>

                                    <form method="POST" action="{{ route('updateAkun', ['nip' => $akun->nip]) }}" class="row">
                                        @csrf
                                        <div class="col">
                                          <div class="mb-3">
                                            <label for="nip" class="form-label">NIP</label>
                                            <input type="text" id="nip" name="nip{{ $akun->nip }}" value="{{ $akun->nip }}" class="form-control @error('nip'.$akun->nip) is-invalid @enderror" required>
                                            @error('nip'.$akun->nip)
                                                <div id="nip" class="invalid-feedback">
                                                  {{ $message }}
                                                </div>
                                            @enderror
                                          </div>
                                            <div class="mb-3">
                                              <label for="nama" class="form-label">Nama</label>
                                              <input type="text" name="nama{{ $akun->nip }}" value="{{ $akun->nama }}" class="form-control @error('nama'.$akun->nip) is-invalid @enderror" required>
                                              @error('nama'.$akun->nip)
                                              <div id="nama" class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                              @enderror
                                            </div>
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group mb-3">
                                              <span class="input-group-text"><i data-feather="mail"></i></span>
                                              <input type="email" name="email{{ $akun->nip }}" value="{{ $akun->email }}" class="form-control @error('email'.$akun->nip) is-invalid @enderror" required>
                                              @error('email'.$akun->nip)
                                                  <div id="email" class="invalid-feedback">
                                                    {{ $message }}
                                                  </div>
                                              @enderror
                                            </div>
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group mb-3">
                                              <span class="input-group-text"><i data-feather="lock"></i></span>
                                              <input type="password" name="password{{ $akun->nip }}" class="form-control @error('password'.$akun->nip) is-invalid @enderror">
                                              @error('password'.$akun->nip)
                                                  <div id="password" class="invalid-feedback">
                                                    {{ $message }}
                                                  </div>
                                              @enderror
                                            </div>
                                        </div>
                                        <div class="col">
                                          <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-select @error('role'.$akun->nip) is-invalid @enderror" id="role" name="role{{ $akun->nip }}" aria-label="Default select example" required>
                                              <option disabled>Pilih role</option>
                                              <option value="pegawai" {{ $akun['role'] == 'pegawai' ? 'selected' : '' }}>pegawai</option>
                                              <option value="atasan" {{ $akun['role'] == 'atasan' ? 'selected' : '' }}>atasan</option>
                                              <option value="sdm" {{ $akun['role'] == 'sdm' ? 'selected' : '' }}>SDM</option>
                                            </select>
                                            @error('role'.$akun->nip)
                                                <div id="nama" class="invalid-feedback">
                                                  {{ $message }}
                                                </div>
                                            @enderror
                                          </div>
                                          <div class="mb-3">
                                            <label for="atasan" class="form-label">Atasan</label>
                                            <select class="form-select @error('atasan'.$akun->nip) is-invalid @enderror" id="atasan" name="atasan{{ $akun->nip }}" aria-label="Default select example" required>
                                              <option disabled>Pilih Atasan</option>
                                              @foreach($atasans as $atasan)
                                                  <option value="{{ $atasan->nip }}" {{ $atasan->nip == $akun->nip_atasan ? 'selected' : '' }}>{{ $atasan->nama }}</option>
                                              @endforeach
                                            </select>
                                            @error('atasan'.$akun->nip)
                                                <div id="nama" class="invalid-feedback">
                                                  {{ $message }}
                                                </div>
                                            @enderror
                                          </div>
                                          <div class="mb-3">
                                            <label for="bidang" class="form-label">Bidang</label>
                                            <select class="form-select @error('bidang'.$akun->nip) is-invalid @enderror" id="bidang" name="bidang{{ $akun->nip }}" aria-label="Default select example" required>
                                              <option disabled>Pilih Bidang</option>
                                              @foreach($bidangs as $bidang)
                                                  <option value="{{ $bidang->id_bidang }}" {{ $bidang->id_bidang == $akun->id_bidang ? 'selected' : '' }}>{{ $bidang->bidang }}</option>
                                              @endforeach
                                            </select>
                                            @error('bidang'.$akun->nip)
                                                <div id="nama" class="invalid-feedback">
                                                  {{ $message }}
                                                </div>
                                            @enderror
                                          </div>
                                        <div class="mb-3">
                                            <label for="jabatan" class="form-label">Jabatan</label>
                                            <select class="form-select @error('jabatan'.$akun->nip) is-invalid @enderror" id="jabatan" name="jabatan{{ $akun->nip }}" aria-label="Default select example" required>
                                              <option disabled>Pilih Jabatan</option>
                                              @foreach($jabatans as $jabatan)
                                                  <option value="{{ $jabatan->id_jabatan }}" {{ $jabatan->id_jabatan == $akun->id_jabatan ? 'selected' : '' }}>{{ $jabatan->jabatan }}</option>
                                              @endforeach
                                            </select>
                                            @error('jabatan'.$akun->nip)
                                                <div id="nama" class="invalid-feedback">
                                                  {{ $message }}
                                                </div>
                                            @enderror
                                          </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-center">
                                          <button type="submit" class="btn btn-primary w-auto">Submit</button>
                                        </div>
                                    </form>
                                    <a href="#" class="modal__close"><h1>&times;</h1></a>
                                </div>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <a href="#edit{{ $akun->nip }}" class="btn btn-outline-primary"><i data-feather="edit"></i></a>
                                <a href="javascript:void(0)" onclick="deleteAkun('{{ $akun->nip }}')" class="btn btn-outline-danger"><i data-feather="trash-2"></i></a>
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        $('#myTable').DataTable({"pageLength": 25});
    });
    function deleteAkun(nip) {
        if (confirm('Anda yakin ingin menghapus akun ini?')) {
            $.ajax({
                type: 'DELETE',
                url: '/deleteAkun/' + nip,
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
                        $('#deleteForm' + nip).closest('tr').remove();
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
  <script src="{{ asset('DataTables/dataTables.js') }}" type="text/javascript" language="javascript"></script>
</body>
</html>
