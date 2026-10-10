@extends('main')

@section('contents')
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key text-danger" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z"/>
                        <path d="M15 9h.01"/>
                    </svg>
                </div>
                <p class="empty-title fs-2">{{ $title ?? 'Gagal Membuka KYC SatuSehat' }}</p>
                <p class="empty-subtitle text-secondary">
                    {{ $message ?? 'Terjadi kesalahan saat memproses permintaan validasi KYC.' }}
                </p>
                <div class="empty-action mt-3">
                    <a href="javascript:window.close()" class="btn btn-outline-secondary me-2">
                        Tutup Halaman
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
