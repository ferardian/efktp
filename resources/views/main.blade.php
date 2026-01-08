<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-title/>
    <!-- CSS files -->
    <link href="{{ asset('public/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/css/demo.min.css') }}" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('public/img/icon-app.svg') }}">
    <link href="{{ asset('public/css/datatable/dataTables.bootstrap5.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/css/datatable/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('public/css/tabler-icon/tabler-icons.min.css') }}">

    <link href="{{ asset('public/css/select2/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('public/css/select2-custom-darkmode.css') }}" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ asset('public/css/jquery.contextMenu.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome/all.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('public/js/sweetalert/sweetalert2@11.js') }}"></script>
    <style>
        /* @import url('https://rsms.me/inter/inter.css'); */

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            --tblr-border-radius: var(--tblr-border-radius-lg);
            --tblr-border-color: #e1e1e1; /* warna border */
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            font-size: 11px;
        }

        .ti {
            font-size: medium;
            font-weight: lighter;
        }

        .table {
            vertical-align: middle;
            font-size: 10px;
        }

        .table .btn-sm {
            font-size: 10px;
            /*font-size: 10px;*/
            /*padding: var(--tblr-btn-padding-x);*/
            /*border-radius: var(--tblr-border-radius-lg);*/
        }

        .btn-sm {
            font-size: .7rem;
            padding: 4px 10px !important;
            border-radius: var(--tblr-border-radius-lg);
        }


        .form-label {
            font-size: 10px;
            margin-bottom: 0;
        }

        .form-control,
        .form-select {
            font-size: 10px;
            padding: .5rem;
        }

        .input-group-text {
            font-size: 10px;
        }

        textarea {
            resize: none;
        }

        .datepicker {
            font-size: 10px;
        }

        /*.modal {*/
        /*    border-radius: 6px;*/
        /*}*/

        .accordion-button {
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 11px;
        }

        .dropdown-item {
            font-size: 11px;
        }

        .select2-selection__rendered {
            line-height: 35px !important;
        }


        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            height: 35px !important;
        }

        .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            padding-left: 0px !important;
            height: 23px !important;
            vertical-align: top;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__display,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            cursor: default;
            /* padding-left: 2px; */
            /* padding-right: 5px; */
            position: relative;
            top: -8px;
        }

        .select2-selection__arrow {
            height: 35px !important;
        }

        .gigi_posterior {
            background: url({{ asset('public/img/gigi/posterior.png') }});
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .gigi_anterior {
            background: url({{ asset('public/img/gigi/anterior.png') }});
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .gigi {
            height: 40px !important;
            width: 40px !important;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        .separator {
            font-size: 11px;
            color: #6e6e6e;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ced4da;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }

        .text-brand {
            transition: .2s linear;
        }

        .text-brand:hover {
            color: #c7c7c7;
        }

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        table.table {
            width: 100% !important;
        }

        @media (min-width: 1440px) {
            .table {
                font-size: 11px;
            }

            .table .btn-sm {
                font-size: 11px;
            }

            .form-label {
                font-size: 11px;
            }

            .form-control,
            .form-select {
                font-size: 11px;
                padding: .5rem;
            }

            .input-group-text {
                font-size: 11px;
            }

            .datepicker {
                font-size: 11px;
            }
        }

        @media (max-width: 1024px) {
            .text-brand {
                display: none
            }
        }

        .dropzone {
            border: 1px solid rgb(227 214 214 / 80%) !important;
        }

        .datagrid.responsive {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .datagrid.grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.7rem; /* jarak antar kolom/baris */
        }

        .datagrid.grid-3 .datagrid-item {
            border-bottom: 1px solid var(--tblr-border-color);
            padding: 0.5rem 0.5rem;
            background-color: var(--tblr-bg-surface);
        }

        /* span rules */
        .datagrid-item.span-2 {
            grid-column: span 2;
        }

        .datagrid-item.span-3 {
            grid-column: span 3;
        }

        /* Optional: responsive behavior */
        @media (max-width: 768px) {
            .datagrid.grid-3 {
                grid-template-columns: 1fr;
            }
        }

        .datagrid.grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }


        div.dataTables_wrapper div.dataTables_length select {
            width: 50% !important; /* biar dropdown tidak terlalu lebar */
            display: inline-block !important;
            border-radius: var(--tblr-border-radius-lg)
        }

        div.dataTables_wrapper div.dataTables_filter input[type=search] {
            border-radius: var(--tblr-border-radius-lg)
        }


    </style>
    <style>
        .offcanvas .navbar-collapse .dropdown-menu {
            padding: 0;
            background: 0 0;
            border: 0 !important;
            position: static !important;
            color: inherit;
            box-shadow: none;
            min-width: 0;
            margin: 0 !important;
            transform: none !important;
            /*inset: none ! inset;*/

        }

        .offcanvas .navbar-collapse .dropdown-menu .dropdown-item {
            min-width: 0;
            display: flex;
            width: auto;
            padding-left: calc(calc(calc(var(--tblr-page-padding) * 2) / 2) + 0.5rem);
            color: inherit;
        }

        .offcanvas .navbar-collapse .dropdown-menu .dropdown-menu .dropdown-item {
            padding-left: calc(calc(calc(var(--tblr-page-padding) * 2) / 2) + 1.5rem);
        }
    </style>
    @stack('style')
</head>

<body class="layout-fluid">

<script src="{{ asset('public/js/demo-theme.min.js') }}"></script>

<div class="page-wrapper">
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast text-bg-success border-0 shadow-lg" id="toast-simple" role="alert" aria-live="assertive"
             aria-atomic="true"
             data-bs-autohide="true" data-bs-toggle="toast">
            <div class="toast-body d-flex justify-content-between">
                    <span class="me-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l5 5l10 -10"/>
                        </svg>
                        Hello, world! This is a toast message.
                    </span>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    @yield('contents')
</div>
<div class="offcanvas offcanvas-end offcanvas-dark w-25" tabindex="-1" id="otherMenu" aria-labelledby="otherMenuLabel"
     aria-modal="true" role="dialog">
    @include('components.offcanvas')
</div>
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
{{-- Datatable --}}
<script src="{{ asset('public/js/dataTable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dataTable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('public/js/dataTable/dataTables.fixedColumns.min.js') }}"></script>
{{-- Datepicker --}}
<script src="{{ asset('public/js/bootstrap-datepicker.min.js') }}"></script>
{{-- Select2 --}}
<script src="{{ asset('public/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('public/js/demo.min.js') }}" defer></script>
<script src="{{ asset('public/js/tabler.min.js') }}" defer></script>
<script src="{{ asset('public/libs/tinymce/tinymce.js') }}" defer></script>
<script src="{{ asset('public/js/contextMenu/jquery.contextMenu.min.js') }}" defer></script>
<script src="{{ asset('public/js/contextMenu/jquery.ui.position.js') }}" defer></script>
<script src="{{ asset('public/js/utility.js') }}" defer></script>
<script>
    window.showToast = function (message, type = 'success', delay = 3000) {
        const toastEl = document.getElementById('toast-simple');
        let iconSVG = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>';
        switch (type) {
            case 'error':
                type = 'danger';
                iconSVG = `<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>`;
                break;
            case 'warning':
                type = 'warning';
                iconSVG = `<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>`;
                break;
            default:
                type = 'success';
                iconSvg = `<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>`
                break;
        }


        toastEl.className = `toast text-bg-${type} border-0 shadow-lg`;
        toastEl.setAttribute('data-bs-delay', delay);
        toastEl.querySelector('.toast-body span').innerHTML = `${iconSVG} <span class="ms-2">${message}</span>`;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    //Script main
    let tanggal = "{{ date('d-m-Y') }}"
    let url = "{{ url('') }}"
    let tglAwal = localStorage.getItem('tglAwal') ? localStorage.getItem('tglAwal') : tanggal;
    let tglAkhir = localStorage.getItem('tglAkhir') ? localStorage.getItem('tglAkhir') : tanggal;

    (function (factory) {
        typeof define === 'function' && define.amd ? define(factory) :
            factory();
    })((function () {
        'use strict';

        const themeStorageKey = "tablerTheme";
        const defaultTheme = "light";
        let selectedTheme;
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: function get(searchParams, prop) {
                return searchParams.get(prop);
            }
        });
        if (!!params.theme) {
            localStorage.setItem(themeStorageKey, params.theme);
            selectedTheme = params.theme;
        } else {
            var storedTheme = localStorage.getItem(themeStorageKey);
            selectedTheme = storedTheme ? storedTheme : defaultTheme;
        }
        if (selectedTheme === 'dark') {
            document.body.setAttribute("data-bs-theme", selectedTheme);
        } else {
            document.body.removeAttribute("data-bs-theme");
        }

    }));

    $(document).ready(() => {
        var tanggal = "{{ date('d-m-Y') }}";
        jam();
        var tglAwal = localStorage.getItem('tglAwal') ? localStorage.getItem('tglAwal') : tanggal;
        var tglAkhir = localStorage.getItem('tglAkhir') ? localStorage.getItem('tglAkhir') : tanggal;

        $('#tglAwal').val(tglAwal)
        $('#tglAkhir').val(tglAkhir)

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.filterTangal').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayBtn: true,
            todayHighlight: true,
            language: "id",
        });

        $('.filterTahun').datepicker({
            dateFormat: "yyyy",
            viewMode: "years",
            minViewMode: "years",

        }).on('changeDate', function (e) {
            var selectedYear = e.date.getFullYear();
            $(this).val(selectedYear)
        }).on('hide', function (e) {
            var selectedYear = $(this).datepicker('getDate').getFullYear();
            $(this).val(selectedYear); // Set input value to the selected year
        });

        $('.form-select-2').select2({
            width: '100%',
        })

    })


    function switcTab(tabElement, target = '') {
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');

        if (target) {
            const element = tabElement.find(`a[href="#${target}"]`)
            $(element).addClass('active')
            $(target).addClass('show active');
        } else {
            tabElement.find('a').each((index, element) => {
                if (index === 0) {
                    const target = $(element).attr('href')
                    $(element).addClass('active')
                    $(target).addClass('show active');
                }
            })
        }

    }

    function setStatusLayan(no_rawat, status) {
        return $.post(`/efktp/registrasi/update/status`, {
            stts: status,
            no_rawat: no_rawat
        }).done(() => {
            const btn = $(`#btnStatusLayanan${formatNoRawat(no_rawat)}`)
                .removeAttr('class');
            const statusConfig = {
                'Belum':      { class: 'btn btn-sm btn-primary', text: 'BELUM', onclick: (no_rawat) => `setPanggil('${no_rawat}', this)` },
                'Berkas Diterima': { class: 'btn btn-sm btn-purple', text: 'PANGGIL', onclick: (no_rawat) => `setBelum('${no_rawat}', this)` },
                'Batal':      { class: 'btn btn-sm btn-danger', text: 'BATAL' },
                'Sudah':      { class: 'btn btn-sm btn-success', text: 'SUDAH' },
                'Dirawat':    { class: 'btn btn-sm btn-cyan', text: 'DIRAWAT', onclick: (no_rawat) => `setBelum('${no_rawat}', this)` },
                'Dirujuk':    { class: 'btn btn-sm btn-warning', text: 'DIRUJUK' }
            }

            const cfg = statusConfig[status]

            if (cfg) {
                btn
                    .attr('class', cfg.class)
                    .text(cfg.text)
                    .removeAttr('onclick')
                if (cfg.onclick) btn.attr('onclick', cfg.onclick(no_rawat))
            }
        }).fail((error, status, code) => {
            if (error.status !== 500) {
                const errorMessage = {
                    status: error.status,
                    statusText: code,
                    responseJSON: error.responseJSON.message,
                }
                alertErrorAjax(errorMessage)

            } else {
                alertErrorAjax(error)
            }
        });
    }

    function getRegDetail(no_rawat) {
        const registrasi = $.get(`/efktp/registrasi/get/detail`, {
            no_rawat: no_rawat,
        })
        return registrasi;
    }

    function getRegPeriksa(...params) {
        const registrasi = $.get(`/efktp/registrasi/get`, params)
        return registrasi;
    }

    function createAlergi(data) {
        const alergi = $.post(`/efktp/pasien/alergi`, {
            no_rkm_medis: data.no_rkm_medis,
            alergi: data.alergi
        });
        return alergi;
    }

    function getPemeriksaanRalan(no_rawat, nip = '') {
        return $.get(`/efktp/pemeriksaan/ralan/show`, {
            no_rawat: no_rawat,
            nip: nip
        })
    }

    function riwayatIcare(no_peserta) {
        loadingAjax();
        $.get(`/efktp/icare`, {
            no_peserta: no_peserta,
        }).done((response) => {
            if (response.metaData.code === 200) {
                loadingAjax().close();
                window.open(response.response.url, 'Riwayat Perawatan Icare', "width=" + screen.availWidth + ",height=" + screen.availHeight)
            } else {
                Swal.fire(
                    'Peringatan',
                    response.metaData.message,
                    'warning'
                )
            }
        })
    }
</script>
{{-- currency formater --}}
<script>
    const currencyFormatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    });

    const floatFormatter = new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 0,

    });

    function formatCurrency(value) {
        return currencyFormatter.format(value);
    }

    function formatFloat(value) {
        const float = parseFloat(value).toFixed(1);
        return floatFormatter.format(float);
    }

    function jam() {
        setTime = setInterval(() => {
            var dateString = new Date().toLocaleString("id-ID", {
                timeZone: "Asia/Jakarta"
            });
            var formattedString = dateString.replace(",", "-");
            var splitarray = new Array();
            splitarray = formattedString.split(" ");
            var splitarraytime = new Array();
            splitarraytime = splitarray[1].split(".");
            const jamHitung = splitarraytime[0] + ':' + splitarraytime[1] + ':' + splitarraytime[2]; // time
            $('#jam').html(jamHitung)
        }, 1000);
        return setTime;
    }

    function speak(text) {
        if ('speechSynthesis' in window) {
            const u = new SpeechSynthesisUtterance(text);

            // tunggu voices ready
            // speechSynthesis.onvoiceschanged = () => {
            let voices = speechSynthesis.getVoices();
            console.log("Available voices:", voices);

            let indoVoice = voices.find(v => v.lang === 'id-ID');
            if (indoVoice) {
                u.voice = indoVoice;
            }
            u.lang = "id-ID";
            u.rate = 0.9;
            u.pitch = 1;
            u.volume = 1;

            speechSynthesis.speak(u);
            // };
        } else {
            alert("Browser tidak mendukung Web Speech API.");
            console.error("Browser tidak mendukung Web Speech API.");
        }
    }

    function pauseVideo() {
        const iframe = document.querySelector("#frameVideo iframe");
        iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
    }

    function playVideo() {
        const iframe = document.querySelector("#frameVideo iframe");
        iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
    }



    function setTableHeight() {
        const winH = window.innerHeight;
        const winW = window.innerWidth;

        let vhRatio;
        if (winW >= 1920) vhRatio = 0.54;       // PC besar (Full HD ke atas)
        else if (winW >= 1600) vhRatio = 0.52;
        else if (winW >= 1366) vhRatio = 0.49;
        else if (winW >= 1280) vhRatio = 0.46;
        else if (winW >= 992) vhRatio = 0.42;
        else if (winW >= 768) vhRatio = 0.39;
        else if (winW >= 576) vhRatio = 0.36;
        else vhRatio = 0.33; // mobile kecil

        const tableH = Math.max(winH * vhRatio, 200); // minimal 200px
        return tableH + 'px';
    }


</script>
@stack('script')
</body>

</html>
