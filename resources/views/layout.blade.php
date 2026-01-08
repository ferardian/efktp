@extends('main')


@section('contents')
    {{-- page header : informasi header --}}

    <x-navbar/>
    <div class="page-body my-1">
        <div class="container-xl">
            <div class="page-header d-print-none my-2">
                <div class="container-xl">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h6 class="page-title" style="font-size:0.9rem">
                                @if (count(Request::segments()))
                                    @for ($i = 1; $i <= count(Request::segments()); $i++)
                                        {{ ucwords(str_replace('-',' ',Request::segment($i))) }}
                                        @if (($i < count(Request::segments())) & ($i >= 1))
                                            >
                                        @endif
                                    @endfor
                                @else
                                    Beranda
                                @endif
                            </h6>
                        </div>
                        <div class="col text-end">
                            <h4 class="h4 mb-0"><i class="ti ti-clock me-1"></i>{{ now()->translatedFormat('j F Y') }}
                                <span id="jam" class="ms-1">{{ date('H:i:s') }}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            @yield('body')
        </div>
    </div>
    {{-- </div> --}}
@endsection
