<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-alert">
    <div
        {{ $attributes->merge([
            'class' => 'toast border-0 shadow-lg text-bg-' . ($type ?? 'success'),
            'role' => 'alert',
            'aria-live' => 'assertive',
            'aria-atomic' => 'true',
            'data-bs-autohide' => 'true',
            'data-bs-delay' => $delay ?? 3000,
        ]) }}>
        <div class="toast-body d-flex justify-content-between align-items-center">
            <span class="me-auto d-flex align-items-center gap-2">
                {{-- Icon otomatis sesuai type --}}
                @if (($type ?? 'success') === 'success')
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                @elseif(($type ?? 'success') === 'error')
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                @elseif(($type ?? 'success') === 'warning')
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v2m0 4h.01" />
                        <path d="M5 19h14L12 5z" />
                    </svg>
                @endif

                {{ $slot }}
            </span>

            <button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
