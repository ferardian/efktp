<title>
    @if (count(Request::segments()))
        @for ($i = 1; $i <= count(Request::segments()); $i++)
            {{ ucwords(str_replace('-', ' ', Request::segment($i))) }}
            @if (($i < count(Request::segments())) & ($i >= 1))
                -
            @endif
        @endfor
        :: {{ $data->nama_instansi }}
    @else
        {{ $data->nama_instansi }}
    @endif
</title>
