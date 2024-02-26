<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PLN</title>
  
  <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  
  
</head>
<body>
  <div class="wrapper">
    @include('layout.sidebar')
    <div class="main">
    @include('layout.navbar')

  @if(auth()->user()->role == 'atasan')
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
  @endif
    <main class="content">
      <div class="container-fluid p-0">
        @if(auth()->user()->role == 'atasan')
        <h1 class="h3 mb-3"><strong>Dashboard Atasan</strong></h1>
        @endif
        @if(auth()->user()->role == 'sdm')
        <h1 class="h3 mb-3"><strong>Dashboard SDM</strong></h1>
        @endif

        <div class="row ">
          <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
              <div class="row">
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Jumlah Pegawai</h5> 
                        </div>
                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="users"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-1 mb-3">{{ $jumlahPegawai }}</h1>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Disetujui</h5>
                        </div>

                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="thumbs-up"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-4 mb-4">{{ $disetujui1 }}</h1>
                      <div class="mb-0">
                        <span class="@if($persenDisetujui >= 0) text-success @else text-danger @endif"> <i class="mdi mdi-arrow-bottom-right"></i>{{ $persenBelumDisetujui }}%</span>
                        <span class="text-muted">dari bulan lalu</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Belum Disetujui</h5>
                        </div>

                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="x-circle"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-1 mb-3">{{ $belumDisetujui1 }}</h1>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">Tidak Disetujui</h5>
                        </div>

                        <div class="col-auto">
                          <div class="stat text-primary">
                            <i class="align-middle" data-feather="thumbs-down"></i>
                          </div>
                        </div>
                      </div>
                      <h1 class="mt-4 mb-4">{{ $tidakDisetujui1 }}</h1>
                      <div class="mb-0">
                        <span class="@if($persenTidakDisetujui >= 0) text-success @else text-danger @endif"> <i class="mdi mdi-arrow-bottom-right"></i>{{ $persenTidakDisetujui }}%</span>
                        <span class="text-muted">dari bulan lalu</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-xxl-7">
            <div class="card flex-fill">
              <div class="card-header">
                <h5 class="card-title mb-0">Laporan Izin Bulanan</h5>
              </div>
              <div class="card-body d-flex w-100">
                <div class="align-self-center chart chart-sm">
                  <canvas id="chartjs-dashboard-bar"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 d-flex">
            <div class="card flex-fill">
              <div class="card-header">

                <h5 class="card-title mb-0">Kalender Izin</h5>
              </div>
              <div class="card-body py-3">
                <div class="chart chart-sm">
                  <div id="calendar"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12 d-flex">
            <div class="card flex-fill">
              <div class="card-header">

                <h5 class="card-title mb-0">Jumlah Izin Pegawai</h5>
              </div>
              <div class="card-body py-3">
                <div class="chart chart-sm">
                  <canvas id="chartjs-pegawai-line"></canvas>
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
  <!-- <script src="{{ URL::asset('js/charts.js') }}"></script> -->
  <script>
    $(document).ready(function() {
      var izin = @json($events);

      $('#calendar').fullCalendar({
        defaultView: 'month',
        timeFormat: 'H:mm',
        events: izin,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,listWeek,listDay,dayGridWeek,dayGridDay'
          },
        height: 'auto',
        now: moment(), // Set the current date and time
        nowIndicator: true,
        views: {
          dayGridDay: {}
        }

      })
    });
    document.addEventListener("DOMContentLoaded", function() {
      new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
					datasets: [{
						label: "Bulan ini",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [
                      @for ($i = 1; $i <= 12; $i++)
                          {{ $countsPerMonth[$i]}}{{ $i < 12 ? ',' : '' }}
                      @endfor
                  ],
						barPercentage: .75,
						categoryPercentage: .5
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: true,
							ticks: {
								stepSize: 10
							}
						}],
						xAxes: [{
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
      });
      var data1 = <?php echo json_encode($data1); ?>;
      var data2 = <?php echo json_encode($data2); ?>;

      var labels1 = Object.keys(data1);
      var values1 = Object.values(data1);
      var values2 = Object.values(data2);


      new Chart(document.getElementById("chartjs-pegawai-line"), {
				type: "horizontalBar",
				data: {
					labels: labels1,
					datasets: [
            {
						label: "Pribadi",
            backgroundColor: 'rgba(169, 169, 169, 0.7)', 
            borderColor: 'rgba(169, 169, 169, 1)',        
            hoverBackgroundColor: 'rgba(169, 169, 169, 0.9)',
            hoverBorderColor: 'rgba(169, 169, 169, 1)',      
						data: values1,
            barPercentage: .75,
            stack: 'Stack 0',
					},{
						label: "Dinas",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: values2,
            barPercentage: .75,
						stack: 'Stack 0',
					},
        ]
				},
				options: {
          plugins: {
            title: {
              display: true,
              text: 'Chart.js Bar Chart - Stacked'
            },
          },
          responsive: true,
          interaction: {
            intersect: false,
          },
          scales: {
            x: {
              stacked: true
            },
            y: {
              stacked: true
            }
          }
        }
      });
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
                  {{ $countsIzinPerMonth[$i] }}{{ $i < 12 ? ',' : '' }}
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
</body>
</html>
