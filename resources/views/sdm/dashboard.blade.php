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
        <h1 class="h3 mb-3"><strong>Dashboard admin</strong></h1>

        <div class="row">
          <div class="col-xl-5 col-xxl-5 d-flex">
            <div class="w-100">
              <div class="row">
                <div class="col-sm-7">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Izin Disetujui</h5>
                        </div>
                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="check-circle"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-1 mb-3 text-3xl">2.382</h1>
                      <div class="mb-0">
                        <span class="text-muted">Dalam bulan ini</span>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Izin Tidak Disetujui</h5>
                        </div>

                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="x-circle"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-1 mb-3 text-3xl">14.212</h1>
                      <div class="mb-0">
                        <span class="text-muted">Dalam bulan ini</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-7 col-xxl-7">
            <div class="card flex-fill w-100">
              <div class="card-header">
                <h5 class="card-title mb-0">Calendar</h5>
              </div>
              <div class="card-body py-3">
                <div class="chart chart-sm">
                  <canvas id="chartjs-dashboard-line"></canvas>
                </div>
              </div>
            </div>
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
