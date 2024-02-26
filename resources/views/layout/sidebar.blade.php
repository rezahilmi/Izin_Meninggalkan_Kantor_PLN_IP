<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="/">
            <div class="rounded overflow-hidden bg-white p-1">
                <img src="{{ URL::asset('img/pln.png') }}" alt="" width="190px" class="rounded">
            </div>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Pages
            </li>
            @if(auth()->user()->role == 'pegawai')
                <li class="sidebar-item {{ request()->segment(1) == 'dashboard-pegawai' ? 'active' : '' }}">
                    <a class="sidebar-link" href="/">
                        <div class="d-flex align-items-center">
                            <i class="align-middle" data-feather="pie-chart"></i>
                            <span class="align-middle">Dashboard</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'ajukanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('pengajuanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="file-plus"></i>
                            <span class="align-middle">Ajukan Surat Izin</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'statusIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('statusIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="file-text"></i>
                            <span class="align-middle">Status Izin</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(auth()->user()->role == 'cuti')
            <li class="sidebar-item {{ request()->segment(1) == 'dashboard-atasan' ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboardAtasan') }}">
                    <div class="d-flex align-items-center">
                        <i class="align-middle" data-feather="pie-chart"></i>
                        <span class="align-middle">Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="sidebar-item {{ request()->segment(2) == 'laporanIzin' ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('getLaporanIzin') }}">
                    <div class="d-flex align-items-center">
                        <i data-feather="book-open"></i>
                        <span class="align-middle">Laporan Izin</span>
                    </div>
                </a>
            </li>
            @endif
            @if(auth()->user()->role == 'atasan')
                <li class="sidebar-item {{ request()->segment(1) == 'dashboard-atasan' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dashboardAtasan') }}">
                        <div class="d-flex align-items-center">
                            <i class="align-middle" data-feather="pie-chart"></i>
                            <span class="align-middle">Dashboard</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(2) == 'persetujuanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('getPersetujuanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="check-square"></i>
                            <span class="align-middle">Persetujuan Izin</span>
                            @php
                                $belumDisetujui = App\Models\Pegawai::where('nip_atasan', auth()->user()->nip)
                                                ->join('izin', 'pegawai.nip', '=', 'izin.nip')
                                                ->where('status', 0)
                                                ->count();
                            @endphp
                            @if($belumDisetujui > 0)
                                <span class="badge bg-danger position-absolute top-50 end-0 translate-middle-y me-4">
                                    {{ $belumDisetujui }}
                                </span>
                            @endif
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(2) == 'laporanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('getLaporanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="book-open"></i>
                            <span class="align-middle">Laporan Izin</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-header">
                    Pengajuan Izin
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'ajukanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('pengajuanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="file-plus"></i>
                            <span class="align-middle">Ajukan Surat Izin</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'statusIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('statusIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="file-text"></i>
                            <span class="align-middle">Status Izin</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(auth()->user()->role == 'sdm')
                <li class="sidebar-item {{ request()->segment(1) == 'dashboard-sdm' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dashboardSDM') }}">
                        <div class="d-flex align-items-center">
                            <i class="align-middle" data-feather="pie-chart"></i>
                            <span class="align-middle">Dashboard</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(2) == 'persetujuanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('getPersetujuanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="check-square"></i>
                            <span class="align-middle">Persetujuan Izin</span>
                            @php
                                $belumDisetujui = App\Models\Pegawai::join('izin', 'pegawai.nip', '=', 'izin.nip')
                                                ->where('status', 0)
                                                ->count();
                            @endphp
                            @if($belumDisetujui > 0)
                                <span class="badge bg-danger position-absolute top-50 end-0 translate-middle-y me-4">
                                    {{ $belumDisetujui }}
                                </span>
                            @endif
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(2) == 'laporanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('getLaporanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="book-open"></i>
                            <span class="align-middle">Laporan Izin</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-header">
                    Pengaturan Akun
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'daftarAkun' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('daftarAkun') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="users"></i>
                            <span class="align-middle">Daftar Akun</span>
                        </div>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->segment(1) == 'imporAkun' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('imporAkun') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="download"></i>
                            <span class="align-middle">Impor Akun</span>
                        </div>
                    </a>
                </li>
            @endif
            @if(auth()->user()->role == 'keamanan')
                <li class="sidebar-item {{ request()->segment(2) == 'laporanIzin' ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('getLaporanIzin') }}">
                        <div class="d-flex align-items-center">
                            <i data-feather="book-open"></i>
                            <span class="align-middle">Laporan Izin</span>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
        @if(auth()->user()->role == 'atasan')
        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <div class="d-grid">
                    <a href="{{route('getDaftarPegawai')}}" class="btn btn-primary">Ambil Cuti</a>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->role == 'cuti')
        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <div class="d-grid">
                    <form method="POST" action="{{route('selesaiCuti')}}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Cuti Selesai</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</nav>