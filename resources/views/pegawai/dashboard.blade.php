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
        <h1 class="h3 mb-3"><strong>Dashboard</strong></h1>

        <div class="row">
          <div class="col-md-6 col-xl-3 col-xxl-3 d-flex">
            <div class="w-100">
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
                  <h1 class="mt-1 mb-3 text-3xl">{{$disetujui}}</h1>
                  <div class="mb-0">
                    <span class="text-muted">Dalam tahun ini</span>
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
                  <h1 class="mt-1 mb-3 text-3xl">{{$tidakDisetujui}}</h1>
                  <div class="mb-0">
                    <span class="text-muted">Dalam tahun ini</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
              <div class="card-header">

                <h5 class="card-title mb-0">Keperluan</h5>
              </div>
              <div class="card-body d-flex">
                <div class="align-self-center w-100">
                  <div class="py-3">
                    <div class="chart chart-xs">
                      <canvas id="chartjs-dashboard-pie"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-xxl-6">
            <div class="card flex-fill w-100">
              <div class="card-header">
                <h5 class="card-title mb-0">Jumlah Izin per Bulan</h5>
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
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var countPribadi = {{$countPribadi}};
      var countDinas = {{$countDinas}};
      new Chart(document.getElementById("chartjs-dashboard-pie"), {
          type: "pie",
          data: {
            labels: ["Pribadi [" + countPribadi + "]", "Dinas [" + countDinas + "]"],
            datasets: [{
              data: [countPribadi, countDinas],
              backgroundColor: [
                window.theme.default,
                window.theme.primary,
              ],
              borderWidth: 5
            }]
          },
          options: {
            responsive: !window.MSInputMethodContext,
            maintainAspectRatio: false,
            legend: {
              display: true
            },
            cutoutPercentage: 75
          }
        });
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
        new Chart(document.getElementById("chartjs-dashboard-line"), {
          type: "line",
          data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
              label: "Jumlah Izin",
              fill: true,
              backgroundColor: gradient,
              borderColor: window.theme.primary,
              data: [
                @for ($i = 1; $i <= 12; $i++)
                  {{ $countsPerMonth[$i] }}{{ $i < 12 ? ',' : '' }}
                @endfor
              ],
              lineTension: 0
            }]
          },
          options: {
            maintainAspectRatio: false,
            legend: {
              display: false
            },
            tooltips: {
              intersect: false
            },
            hover: {
              intersect: true
            },
            plugins: {
              filler: {
                propagate: false
              }
            },
            scales: {
              xAxes: [{
                reverse: true,
                gridLines: {
                  color: "rgba(0,0,0,0.0)"
                }
              }],
              yAxes: [{
                ticks: {
                  stepSize: 1000
                },
                display: true,
                borderDash: [3, 3],
                gridLines: {
                  color: "rgba(0,0,0,0.0)"
                }
              }]
            }
          }
        });
      });
  </script>
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <!-- <script src="{{ URL::asset('js/charts.js') }}"></script> -->
</body>
</html>
