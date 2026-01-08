<ul class="navbar-nav">
    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/') }}">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                 </svg>
             </span>
            <span class="nav-link-title">
                 Beranda
             </span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('registrasi') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/registrasi') }}">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-pencil" width="24"
                      height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                     <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                     <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                     <path d="M10 18l5 -5a1.414 1.414 0 0 0 -2 -2l-5 5v2h2z"></path>
                 </svg>
             </span>
            <span class="nav-link-title">
                 Registrasi
             </span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('ranap') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/ranap') }}">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bed-filled" width="24"
                      height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M3 6a1 1 0 0 1 .993 .883l.007 .117v6h6v-5a1 1 0 0 1 .883 -.993l.117 -.007h8a3 3 0 0 1 2.995 2.824l.005 .176v8a1 1 0 0 1 -1.993 .117l-.007 -.117v-3h-16v3a1 1 0 0 1 -1.993 .117l-.007 -.117v-11a1 1 0 0 1 1 -1z"
                           stroke-width="0" fill="currentColor"/>
                     <path d="M7 8a2 2 0 1 1 -1.995 2.15l-.005 -.15l.005 -.15a2 2 0 0 1 1.995 -1.85z" stroke-width="0"
                           fill="currentColor"/>
                 </svg>
             </span>
            <span class="nav-link-title">
                 Rawat Inap
             </span>
        </a>
    </li>
    <li class="nav-item  {{ Request::segment(1) == 'farmasi' ? 'active' : '' }} dropdown">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"
           data-bs-auto-close="outside" role="button" aria-expanded="true">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pill" width="24"
                      height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M4.5 12.5l8 -8a4.94 4.94 0 0 1 7 7l-8 8a4.94 4.94 0 0 1 -7 -7"/>
                     <path d="M8.5 8.5l7 7"/>
                 </svg>
             </span>
            <span class="nav-link-title">
                 Fasmasi
             </span>
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a href="{{ url('farmasi/obat') }}" class="dropdown-item">
                        Obat & BHP
                    </a>
                    <a href="{{ url('farmasi/racik/template') }}"
                       class="dropdown-item {{ Request::is('template') ? 'active' : '' }}">
                        Template Racikan
                    </a>
                    <a href="{{ url('farmasi/resep') }}" class="dropdown-item">
                        Resep Obat
                    </a><a href="{{ url('master/paket-obat') }}" class="dropdown-item">
                        Paket Obat
                    </a>
                </div>
            </div>
        </div>
    </li>
    <li class="nav-item  {{ Request::segment(1) == 'pcare' ? 'active' : '' }} dropdown">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"
           data-bs-auto-close="outside" role="button" aria-expanded="true">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plug-connected" width="24"
                      height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none"
                      stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M7 12l5 5l-1.5 1.5a3.536 3.536 0 1 1 -5 -5l1.5 -1.5z"/>
                     <path d="M17 12l-5 -5l1.5 -1.5a3.536 3.536 0 1 1 5 5l-1.5 1.5z"/>
                     <path d="M3 21l2.5 -2.5"/>
                     <path d="M18.5 5.5l2.5 -2.5"/>
                     <path d="M10 11l-2 2"/>
                     <path d="M13 14l-2 2"/>
                 </svg>
             </span>
            <span class="nav-link-title">
                 Pcare
             </span>
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a href="{{ url('pcare/pendaftaran') }}"
                       class="dropdown-item {{ Request::is('pcare/pendaftaran') ? 'active' : '' }}">
                        Pendaftaran
                    </a>
                    <a href="{{ url('pcare/kunjungan') }}"
                       class="dropdown-item {{ Request::is('pcare/kunjungan') ? 'active' : '' }}">
                        Kunjungan
                    </a>
                    {{-- <a href="{{ url('pcare/rujukan') }}" class="dropdown-item {{ Request::is('pcare/rujukan') ? 'active' : '' }}">
                        Rujuk Keluar
                    </a> --}}
                </div>
            </div>
        </div>
    </li>
    <li class="nav-item {{ Request::is('kyc') ? 'active' : '' }}">
        <a class="nav-link"
           href="{{ url('kyc') }}?nama={{ session()->get('pegawai')->nama }}&nik={{ session()->get('pegawai')->nik }}"
           target="_blank">
             <span class="nav-link-icon d-md-none d-lg-inline-block">
                 <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" width="24" height="24"
                      viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round"
                      stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z"/>
                     <path d="M15 9h.01"/>
                 </svg>
             </span>
            <span class="nav-link-title">
                 KYC
             </span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('antrean') ? 'active' : '' }} dropdown">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown"
           data-bs-auto-close="outside" role="button" aria-expanded="true">
             <span class="nav-link-icon d-md-none d-lg-inline-block mt-1">
                 <i class="ti ti-screen-share fw-2"></i>
             </span>
            <span class="nav-link-title">
                 Antrean
             </span>
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a href="{{ url('antrean/poliklinik') }}" target="_blank" class="dropdown-item">
                        Poliklinik
                    </a>
                    <a href="{{ url('antrean/poliklinik/v2') }}" target="_blank" class="dropdown-item ">
                        Poliklinik v2
                    </a>
                    <a href="{{ url('antrean/farmasi') }}" target="_blank" class="dropdown-item">
                        Farmasi
                    </a>
                </div>
            </div>
        </div>
    </li>


</ul>
