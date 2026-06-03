<form action="" method="post" id="formMcu">
    <div class="row">
        <!-- Patient Info Header (Auto Copied) -->
        <div class="col-md-6 col-xl-3 col-lg-3">
            <div class="mb-2">
                <label class="form-label">No. Rawat</label>
                <input type="text" class="form-control bg-light" name="no_rawat" readonly>
            </div>
        </div>
        <div class="col-md-6 col-xl-5 col-lg-5">
            <div class="mb-2">
                <label class="form-label">Pasien</label>
                <div class="input-group">
                    <input type="text" class="form-control bg-light" name="no_rkm_medis" readonly>
                    <input type="text" class="form-control w-50 bg-light" name="nm_pasien" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 col-lg-4">
            <div class="mb-2">
                <label class="form-label">Dokter Pemeriksa</label>
                <div class="input-group">
                    <input type="text" class="form-control bg-light w-25" name="nip" readonly>
                    <input type="text" class="form-control bg-light w-70" name="nm_dokter" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 1: Pemeriksaan Fisik Dasar -->
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-primary-lt">
            <h3 class="card-title text-primary"><i class="ti ti-activity me-2"></i>1. Pemeriksaan Fisik Dasar</h3>
        </div>
        <div class="card-body">
            <div class="row gy-2">
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">Tinggi Badan</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="tb" placeholder="-" oninput="hitungBmiMcu()">
                        <span class="input-group-text">cm</span>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">Berat Badan</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="bb" placeholder="-" oninput="hitungBmiMcu()">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">BMI (IMT)</label>
                    <input type="text" class="form-control bg-light text-end" name="bmi" readonly placeholder="-">
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label">Klasifikasi BMI</label>
                    <select class="form-select" name="kasifikasi_bmi">
                        <option value="Berat Badan Kurang">Berat Badan Kurang</option>
                        <option value="Berat Badan Normal" selected>Berat Badan Normal</option>
                        <option value="Kelebihan Berat Badan">Kelebihan Berat Badan</option>
                        <option value="Obesitas I">Obesitas I</option>
                        <option value="Obesitas II">Obesitas II</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label">Tekanan Darah</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="td" placeholder="120/80" oninput="formatTensi(this)" onblur="cleanupTensi(this)">
                        <span class="input-group-text">mmHg</span>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">Nadi</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="nadi" placeholder="-">
                        <span class="input-group-text">x/mnt</span>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">Respirasi (RR)</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="rr" placeholder="-">
                        <span class="input-group-text">x/mnt</span>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <label class="form-label">Suhu Tubuh</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control text-end" name="suhu" placeholder="-">
                        <span class="input-group-text">°C</span>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label">Keadaan Umum</label>
                    <select class="form-select" name="keadaan">
                        <option value="Baik" selected>Baik</option>
                        <option value="Tidak Baik">Tidak Baik</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label">Kesadaran</label>
                    <select class="form-select" name="kesadaran">
                        <option value="Composmentis" selected>Composmentis</option>
                        <option value="Apatis">Apatis</option>
                        <option value="Somnolen">Somnolen</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Pemeriksaan Fisik Sistemik -->
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-primary-lt">
            <h3 class="card-title text-primary"><i class="ti ti-stethoscope me-2"></i>2. Pemeriksaan Fisik Sistemik</h3>
        </div>
        <div class="card-body">
            <div class="row gy-2">
                <!-- Jantung & Paru -->
                <div class="col-md-6">
                    <div class="form-fieldset">
                        <h4 class="mb-2 text-muted">Jantung & Paru-paru</h4>
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label class="form-label">Bunyi Napas</label>
                                <select class="form-select" name="bunyi_napas">
                                    <option value="-">-</option>
                                    <option value="Vesikuler" selected>Vesikuler</option>
                                    <option value="Bronkhial">Bronkhial</option>
                                    <option value="Trakeal">Trakeal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bunyi Tambahan</label>
                                <select class="form-select" name="bunyi_tambahan">
                                    <option value="-">-</option>
                                    <option value="Tidak Ada" selected>Tidak Ada</option>
                                    <option value="Wheezing">Wheezing</option>
                                    <option value="Ronchi">Ronchi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bunyi Jantung</label>
                                <select class="form-select" name="bunyi_jantung">
                                    <option value="-">-</option>
                                    <option value="Reguler" selected>Reguler</option>
                                    <option value="Irreguler">Irreguler</option>
                                    <option value="Gallop">Gallop</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Batas Jantung</label>
                                <select class="form-select" name="batas">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Melebar">Melebar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abdomen -->
                <div class="col-md-6">
                    <div class="form-fieldset">
                        <h4 class="mb-2 text-muted">Pemeriksaan Perut/Abdomen</h4>
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label class="form-label">Inspeksi</label>
                                <select class="form-select" name="inspeksi">
                                    <option value="-">-</option>
                                    <option value="Datar" selected>Datar</option>
                                    <option value="Cembung">Cembung</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Palpasi</label>
                                <select class="form-select" name="palpasi">
                                    <option value="-">-</option>
                                    <option value="Supel" selected>Supel</option>
                                    <option value="Tegang (Defans Muscular)">Tegang (Defans Muscular)</option>
                                    <option value="Nyeri Tekan Epigastrium">Nyeri Tekan Epigastrium</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hepar</label>
                                <select class="form-select" name="hepar">
                                    <option value="-">-</option>
                                    <option value="Tidak Membesar" selected>Tidak Membesar</option>
                                    <option value="Membesar">Membesar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Limpa</label>
                                <select class="form-select" name="limpa">
                                    <option value="-">-</option>
                                    <option value="Tidak Membesar" selected>Tidak Membesar</option>
                                    <option value="Membesar">Membesar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tulang Belakang & Anggota Gerak -->
                <div class="col-md-6">
                    <div class="form-fieldset">
                        <h4 class="mb-2 text-muted">Tulang Belakang & Anggota Gerak</h4>
                        <div class="row gy-2">
                            <div class="col-md-4">
                                <label class="form-label">Scoliosis / Kelainan</label>
                                <select class="form-select" name="scoliosis">
                                    <option value="-">-</option>
                                    <option value="Tidak Ada" selected>Tidak Ada</option>
                                    <option value="Ada">Ada</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Ekstremitas Atas</label>
                                <div class="input-group">
                                    <select class="form-select w-40" name="ekstrimitas_atas">
                                        <option value="-">-</option>
                                        <option value="Normal" selected>Normal</option>
                                        <option value="Tidak Normal">Tidak Normal</option>
                                    </select>
                                    <input type="text" class="form-control w-60" name="ekstrimitas_atas_ket" placeholder="-">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ekstremitas Bawah</label>
                                <div class="input-group">
                                    <select class="form-select w-40" name="ekstrimitas_bawah">
                                        <option value="-">-</option>
                                        <option value="Normal" selected>Normal</option>
                                        <option value="Tidak Normal">Tidak Normal</option>
                                    </select>
                                    <input type="text" class="form-control w-60" name="ekstrimitas_bawah_ket" placeholder="-">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kulit & Penglihatan -->
                <div class="col-md-6">
                    <div class="form-fieldset">
                        <h4 class="mb-2 text-muted">Kulit & Penglihatan</h4>
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label class="form-label">Kondisi Kulit</label>
                                <select class="form-select" name="kondisi_kulit">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Tato">Tato</option>
                                    <option value="Penyakit Kulit">Penyakit Kulit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Penyakit Kulit</label>
                                <input type="text" class="form-control" name="penyakit_kulit" placeholder="-">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tes Buta Warna</label>
                                <select class="form-select" name="buta_warna">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Buta Warna Partial">Buta Warna Partial</option>
                                    <option value="Buta Warna Total">Buta Warna Total</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Visus / Ketajaman</label>
                                <input type="text" class="form-control" name="visus" placeholder="-">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendengaran -->
                <div class="col-md-12">
                    <div class="form-fieldset">
                        <h4 class="mb-2 text-muted">Pendengaran / Telinga</h4>
                        <div class="row gy-2">
                            <div class="col-md-3">
                                <label class="form-label">Daun Telinga</label>
                                <select class="form-select" name="daun_telinga">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Tidak Normal">Tidak Normal</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lubang Telinga</label>
                                <select class="form-select" name="lubang_telinga">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Tidak Normal">Tidak Normal</option>
                                    <option value="Lapang">Lapang</option>
                                    <option value="Sempit">Sempit</option>
                                    <option value="Serumen Prop">Serumen Prop</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Selaput Pendengaran</label>
                                <select class="form-select" name="selaput_pendengaran">
                                    <option value="-">-</option>
                                    <option value="Intak" selected>Intak</option>
                                    <option value="Tidak Intak">Tidak Intak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Proc. Mastoideus</label>
                                <select class="form-select" name="proc_mastoideus">
                                    <option value="-">-</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Tidak Normal">Tidak Normal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Pemeriksaan Laboratorium & Pencitraan (Penunjang) -->
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-primary-lt">
            <h3 class="card-title text-primary"><i class="ti ti-flask me-2"></i>3. Pemeriksaan Laboratorium, Pencitraan, & Penunjang</h3>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Laboratorium</label>
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="ambilHasilLab()" id="btnAmbilLab">
                            <i class="ti ti-download me-1"></i> Lampirkan Hasil Lab
                        </button>
                    </div>
                    <textarea class="form-control" rows="5" name="laborat" placeholder="-"></textarea>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Pencitraan (Rontgen Dada / Thorax)</label>
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="ambilHasilRadiologi()" id="btnAmbilRad">
                            <i class="ti ti-download me-1"></i> Lampirkan Hasil Rontgen
                        </button>
                    </div>
                    <textarea class="form-control" rows="5" name="radiologi" placeholder="-"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Audiometri (Nada Murni)</label>
                    <textarea class="form-control" rows="3" name="audiometri" placeholder="-"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pemeriksaan EKG</label>
                    <textarea class="form-control" rows="3" name="ekg" placeholder="-"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Spirometri</label>
                    <textarea class="form-control" rows="3" name="spirometri" placeholder="-"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Treadmill Test</label>
                    <textarea class="form-control" rows="2" name="treadmill" placeholder="-"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Penunjang Lain-lain</label>
                    <textarea class="form-control" rows="2" name="lainlain" placeholder="-"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Kesimpulan & Anjuran -->
    <div class="card mt-3 shadow-sm mb-3">
        <div class="card-header bg-primary-lt">
            <h3 class="card-title text-primary"><i class="ti ti-file-text me-2"></i>4. Kesimpulan & Anjuran</h3>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label">Kesimpulan Akhir</label>
                    <textarea class="form-control" rows="4" name="kesimpulan" placeholder="-"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Anjuran / Saran Medis</label>
                    <textarea class="form-control" rows="4" name="anjuran" placeholder="-"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<script>


    // Format Tekanan Darah (SYS/DIA) input
    function formatTensi(el) {
        let val = el.value.replace(/[^0-9\/]/g, '');
        let prevVal = el.getAttribute('data-prev-val') || '';
        
        // Allow deletion without interference
        if (val.length < prevVal.length) {
            el.value = val;
            el.setAttribute('data-prev-val', val);
            return;
        }
        
        // If there's already a slash, split and restrict parts to 3 digits each
        if (val.indexOf('/') !== -1) {
            let parts = val.split('/');
            let sys = parts[0].substring(0, 3);
            let dia = parts[1] ? parts[1].substring(0, 3) : '';
            val = sys + '/' + dia;
        } else {
            // If no slash, automatically add it when length is >= 3
            if (val.length >= 3) {
                val = val.substring(0, 3) + '/' + val.substring(3, 6);
            }
        }
        
        el.value = val;
        el.setAttribute('data-prev-val', val);
    }

    function cleanupTensi(el) {
        let val = el.value;
        if (val === '/') {
            el.value = '';
        } else if (val.endsWith('/')) {
            el.value = val.slice(0, -1);
        }
    }

    function hitungBmiMcu() {
        const tbInput = $('#formMcu input[name=tb]').val();
        const bbInput = $('#formMcu input[name=bb]').val();
        
        const tb = parseFloat(tbInput.replace(',', '.'));
        const bb = parseFloat(bbInput.replace(',', '.'));

        if (tb > 0 && bb > 0) {
            const tbMeter = tb / 100;
            const bmi = bb / (tbMeter * tbMeter);
            $('#formMcu input[name=bmi]').val(bmi.toFixed(2));

            let klasifikasi = '';
            if (bmi < 18.5) {
                klasifikasi = 'Berat Badan Kurang';
            } else if (bmi >= 18.5 && bmi < 25) {
                klasifikasi = 'Berat Badan Normal';
            } else if (bmi >= 25 && bmi < 27) {
                klasifikasi = 'Kelebihan Berat Badan';
            } else if (bmi >= 27 && bmi < 30) {
                klasifikasi = 'Obesitas I';
            } else {
                klasifikasi = 'Obesitas II';
            }
            $('#formMcu select[name=kasifikasi_bmi]').val(klasifikasi);
        } else {
            $('#formMcu input[name=bmi]').val('-');
        }
    }

    function loadMcu(no_rawat) {
        // Clear form
        $('#formMcu').find('input[type=text], textarea').val('');
        $('#formMcu input[name=td]').removeAttr('data-prev-val');
        $('#formMcu select').each(function() {
            $(this).val($(this).find('option:first').val());
        });

        // Fetch patient details safely to prevent race conditions
        getRegDetail(no_rawat).done((response) => {
            const { pasien, dokter } = response;
            $('#formMcu input[name=no_rawat]').val(response.no_rawat);
            $('#formMcu input[name=no_rkm_medis]').val(response.no_rkm_medis);
            $('#formMcu input[name=nm_pasien]').val(`${pasien.nm_pasien} / ${pasien.jk}`);
            $('#formMcu input[name=nip]').val(response.kd_dokter);
            $('#formMcu input[name=nm_dokter]').val(dokter.nm_dokter);

            // Fetch existing data
            $.get(`{{ url('/pemeriksaan/mcu/get') }}`, { no_rawat: no_rawat }).done(function(data) {
                if (data && Object.keys(data).length) {
                    $('#btnHapusMcu').removeClass('d-none');
                    $('#btnCetakMcu').removeClass('d-none');
                    Object.keys(data).forEach(function(key) {
                        const input = $('#formMcu').find(`input[name=${key}]`);
                        const select = $('#formMcu').find(`select[name=${key}]`);
                        const textarea = $('#formMcu').find(`textarea[name=${key}]`);

                        if (input.length && !input.attr('readonly')) {
                            let val = data[key];
                            if (val === null) {
                                val = '';
                            } else if (val === '-') {
                                val = '';
                            } else if (key === 'td' && (val === '0/0' || val === '0')) {
                                val = '';
                            }
                            input.val(val);
                            if (key === 'td') {
                                formatTensi(input[0]);
                            }
                        }
                        if (textarea.length) {
                            textarea.val(data[key] !== null && data[key] !== '-' ? data[key] : '');
                        }
                        if (select.length) {
                            select.val(data[key]);
                        }
                    });
                    hitungBmiMcu();
                } else {
                    $('#btnHapusMcu').addClass('d-none');
                    $('#btnCetakMcu').addClass('d-none');
                    // If new, copy basic TTV from CPPT form if available
                    const tbCppt = $('#formCpptRajal input[name=tinggi]').val();
                    const bbCppt = $('#formCpptRajal input[name=berat]').val();
                    const tdCppt = $('#formCpptRajal input[name=tensi]').val();
                    const suhuCppt = $('#formCpptRajal input[name=suhu_tubuh]').val();
                    const rrCppt = $('#formCpptRajal input[name=respirasi]').val();
                    const nadiCppt = $('#formCpptRajal input[name=nadi]').val();

                    if (tbCppt && tbCppt !== '0' && tbCppt !== '-') $('#formMcu input[name=tb]').val(tbCppt);
                    if (bbCppt && bbCppt !== '0' && bbCppt !== '-') $('#formMcu input[name=bb]').val(bbCppt);
                    if (tdCppt && tdCppt !== '0' && tdCppt !== '-' && tdCppt !== '0/0') {
                        const tdInput = $('#formMcu input[name=td]');
                        tdInput.val(tdCppt);
                        formatTensi(tdInput[0]);
                    }
                    if (suhuCppt && suhuCppt !== '0' && suhuCppt !== '-') $('#formMcu input[name=suhu]').val(suhuCppt);
                    if (rrCppt && rrCppt !== '0' && rrCppt !== '-') $('#formMcu input[name=rr]').val(rrCppt);
                    if (nadiCppt && nadiCppt !== '0' && nadiCppt !== '-') $('#formMcu input[name=nadi]').val(nadiCppt);
                    hitungBmiMcu();
                }
            });
        });
    }

    function simpanMcu() {
        const no_rawat = $('#formMcu input[name=no_rawat]').val();
        if (!no_rawat || no_rawat === '-') {
            Swal.fire('Error', 'No. Rawat tidak valid', 'error');
            return;
        }

        const data = {};
        $('#formMcu').serializeArray().forEach(function(item) {
            data[item.name] = item.value;
        });

        // Set status layer info (loading state)
        loadingAjax('Sedang menyimpan data MCU...');

        $.post(`{{ url('/pemeriksaan/mcu/create') }}`, data).done(function(response) {
            Swal.close();
            if (response === 'SUKSES') {
                toast('Berhasil menyimpan data MCU');
                $('#modalCppt').modal('hide');
                loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), selectFilterDokter.val(), selectFilterPoli.val());
            } else {
                Swal.fire('Error', 'Gagal menyimpan data MCU', 'error');
            }
        }).fail(function(xhr) {
            Swal.close();
            alertErrorAjax(xhr);
        });
    }

    function ambilHasilLab() {
        const no_rawat = $('#formMcu input[name=no_rawat]').val();
        if (!no_rawat || no_rawat === '-') return;

        $('#btnAmbilLab').prop('disabled', true).html('<i class="ti ti-loader rotate me-1"></i> Loading...');

        $.get(`{{ url('/pemeriksaan/mcu/penunjang') }}`, { no_rawat: no_rawat, type: 'lab' }).done(function(response) {
            $('#btnAmbilLab').prop('disabled', false).html('<i class="ti ti-download me-1"></i> Lampirkan Hasil Lab');
            if (response && response.result) {
                $('#formMcu textarea[name=laborat]').val(response.result);
                toast('Hasil laboratorium berhasil dilampirkan');
            } else {
                toast('Tidak ada hasil laboratorium untuk dilampirkan');
            }
        }).fail(function() {
            $('#btnAmbilLab').prop('disabled', false).html('<i class="ti ti-download me-1"></i> Lampirkan Hasil Lab');
            toast('Gagal mengambil data laboratorium');
        });
    }

    function ambilHasilRadiologi() {
        const no_rawat = $('#formMcu input[name=no_rawat]').val();
        if (!no_rawat || no_rawat === '-') return;

        $('#btnAmbilRad').prop('disabled', true).html('<i class="ti ti-loader rotate me-1"></i> Loading...');

        $.get(`{{ url('/pemeriksaan/mcu/penunjang') }}`, { no_rawat: no_rawat, type: 'radiologi' }).done(function(response) {
            $('#btnAmbilRad').prop('disabled', false).html('<i class="ti ti-download me-1"></i> Lampirkan Hasil Rontgen');
            if (response && response.result) {
                $('#formMcu textarea[name=radiologi]').val(response.result);
                toast('Hasil rontgen berhasil dilampirkan');
            } else {
                toast('Tidak ada hasil rontgen untuk dilampirkan');
            }
        }).fail(function() {
            $('#btnAmbilRad').prop('disabled', false).html('<i class="ti ti-download me-1"></i> Lampirkan Hasil Rontgen');
            toast('Gagal mengambil data rontgen');
        });
    }

    function cetakMcu() {
        const no_rawat = $('#formMcu input[name=no_rawat]').val();
        if (!no_rawat || no_rawat === '-') {
            Swal.fire('Error', 'No. Rawat tidak valid', 'error');
            return;
        }
        window.open(`{{ url('/pemeriksaan/mcu/print') }}?no_rawat=${encodeURIComponent(no_rawat)}`, '_blank');
    }

    function hapusMcu() {
        const no_rawat = $('#formMcu input[name=no_rawat]').val();
        if (!no_rawat || no_rawat === '-') {
            Swal.fire('Error', 'No. Rawat tidak valid', 'error');
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Medical Check Up (MCU) untuk pasien ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Sedang menghapus data MCU...');
                $.post(`{{ url('/pemeriksaan/mcu/delete') }}`, {
                    no_rawat: no_rawat,
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    Swal.close();
                    if (response === 'SUKSES') {
                        toast('Berhasil menghapus data MCU');
                        loadMcu(no_rawat);
                        loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), selectFilterDokter.val(), selectFilterPoli.val());
                    } else {
                        Swal.fire('Error', 'Gagal menghapus data MCU', 'error');
                    }
                }).fail(function(xhr) {
                    Swal.close();
                    alertErrorAjax(xhr);
                });
            }
        });
    }
</script>
