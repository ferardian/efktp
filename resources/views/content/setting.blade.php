@extends('layout')

@push('style')
    <style>
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }
    </style>
@endpush
@section('body')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <i class="ti ti-checklist"></i>
                    </div>
                    <div>
                        <h4 class="alert-title">{{ session('status.title') }}</h4>
                        <div class="text-secondary">{{ session('status.message') }}</div>
                    </div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif
        <div class="row gy-2">
            <div class="container-xl">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-12 col-md-3 border-end">
                            <div class="card-body">
                                <div class="list-group list-group-transparent" id="settings-links">
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active" data-target="pcare">Setting PCARE</a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-target="antrean">Display Antrean</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-9 d-flex flex-column">
                            <div class="card-body" style="height: 80vh;overflow-y:auto">
                                <div class="content-section" id="pcare">
                                    @include('content.setting._pcareSetting')
                                </div>
                                <div class="content-section" id="antrean">
                                    @include('content.setting._videoAntreanSetting')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            $('#settings-links a').on('click', function(event) {
                event.preventDefault();

                // Remove 'active' class from all links and content sections
                $('#settings-links a').removeClass('active');
                $('.content-section').removeClass('active');

                // Add 'active' class to clicked link
                $(this).addClass('active');

                // Show the corresponding content section
                var targetId = $(this).data('target');
                $('#' + targetId).addClass('active');
            });

            // Initially show the first section
            $('#settings-links a').first().click();

            getSettingPcare()
            getVideoAntrean()

        });

        function toggleSettingPcare(e) {
            const trigger = e.currentTarget
            const target = e.currentTarget.dataset.target
            const el = $(`${target}`);
            const isText = el.attr('type') === 'text' ? true : false;
            if (isText) {
                $(trigger).html('<i class="ti ti-eye-off"></i>').attr('class', 'btn btn-outline-secondary')
                el.attr('type', 'password')
            } else {
                $(trigger).html('<i class="ti ti-eye"></i>').attr('class', 'btn btn-danger')
                el.attr('type', 'text')
            }

        }
    </script>
@endpush
