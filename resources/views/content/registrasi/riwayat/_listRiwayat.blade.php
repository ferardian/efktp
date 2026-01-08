<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tanggal Kunjungan</h3>
        <div class="card-actions btn-actions" id="nav-riwayat" style="display: none">
            <a href="#" class="btn-action" id="btn-action-riwayat">
                <i class="ti ti-menu-2"></i>
            </a>
        </div>
    </div>
    <div class="list-group list-group-flush" id="listRiwayatRegistrasi">


    </div>
</div>
@push('script')
    <script>
        function setListRiwayatRegistrasi(data) {
            let list = ``;
            const element = data.map((item, index) => {
                index === 0 ? setContentRiwayat(item.no_rawat) : '';
                const color = item.status_lanjut.toUpperCase() === 'RALAN' ? 'text-indigo' : 'text-yellow';
                list += `<a href="javascript:void(0)" class="list-group-item list-group-item-action ${index===0 ? 'active' : ''}" onclick="setContentRiwayat('${item.no_rawat}');setActive(this)" aria-current="true"><i class="ti ti-circle-check-filled ${color}"></i> ${item.status_lanjut.toUpperCase()} : ${splitTanggal(item.tgl_registrasi)}</a>`
            });
            listRiwayatRegistrasi.append(list)
        }

        function setActive(params) {
            listRiwayatRegistrasi.find('.active').removeClass('active')
            $(params).addClass('active')
        }

        $('#btn-action-riwayat').on('click', () => {
            $('#listRiwayatRegistrasi').toggle();
        })
    </script>
@endpush
