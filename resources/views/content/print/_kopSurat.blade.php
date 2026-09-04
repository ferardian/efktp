@php
    $settingObj = $setting ?? \App\Models\Setting::first();
    $namaInstansi = $data['nama_instansi'] ?? $settingObj->nama_instansi ?? '';
    $alamatInstansi = $data['alamat_instansi'] ?? ($settingObj ? "{$settingObj->alamat_instansi}, {$settingObj->kabupaten}, {$settingObj->propinsi}" : '');
    $kontak = $data['kontak'] ?? $settingObj->kontak ?? '';
    $email = $data['email'] ?? $settingObj->email ?? '';

    // Handle logo base64
    $logoBase64 = '';
    if (!empty($data['logo'])) {
        $logoBase64 = $data['logo'];
    } elseif ($settingObj && !empty($settingObj->logo)) {
        $logoBase64 = base64_encode($settingObj->logo);
    }

    $align = $align ?? 'center';
    $showLine = $showLine ?? true;
@endphp

<table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;" border="0">
    <tr>
        @if(!empty($logoBase64))
            <td style="width: 75px; vertical-align: middle; text-align: center; padding-right: 10px;">
                <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="Logo" style="width: 65px; height: auto; max-height: 75px;">
            </td>
        @endif

        <td style="text-align: {{ $align }}; vertical-align: middle;">
            {{-- Nama Instansi (Mendukung '|' untuk pemisah baris baru) --}}
            <div style="font-size: 15px; font-weight: bold; text-transform: uppercase; line-height: 1.3;">
                {!! nl2br(e(str_replace('|', "\n", $namaInstansi))) !!}
            </div>

            {{-- Alamat & Kontak (Mendukung '|' untuk pemisah baris baru) --}}
            <div style="font-size: 11px; margin-top: 3px; line-height: 1.35; color: #111;">
                {!! nl2br(e(str_replace('|', "\n", $alamatInstansi))) !!}
                @if($kontak || $email)
                    <br>
                    <span>
                        @if($kontak) Telp: {{ $kontak }} @endif
                        @if($kontak && $email) &bull; @endif
                        @if($email) Email: {{ $email }} @endif
                    </span>
                @endif
            </div>
        </td>

        @if(!empty($logoBase64) && $align === 'center')
            {{-- Penyeimbang kanan agar teks tengah simetris sempurna di titik tengah kertas --}}
            <td style="width: 75px;"></td>
        @endif
    </tr>
</table>

@if($showLine)
    {{-- Garis ganda resmi kop surat (garis tebal 2px + garis tipis 1px) --}}
    <div style="border-top: 2px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 4px; margin-bottom: 14px;"></div>
@endif
