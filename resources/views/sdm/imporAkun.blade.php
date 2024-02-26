<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PLN</title>
  
  <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
  
</head>
<body>
  <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

    <main class="content" id="main-content">
      <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Impor Akun</strong></h1>
        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('imporPegawai') }}" class="row" enctype="multipart/form-data">
              @csrf
              <label for="formFile" class="form-label">Unggah file excel data akun pegawai</label>
              <div class="input-group mb-3">
                <input class="form-control" type="file" name="file" id="formFile">
                <button type="submit" class="btn btn-success w-auto">Impor <span><i data-feather="download"></i></span></button>
              </div>
              <small class="text-muted"><span style="color: red;">Silakan unggah file dalam format '.xlsx'</span></small>
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
</body>
</html>
