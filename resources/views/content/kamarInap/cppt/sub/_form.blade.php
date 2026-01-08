<form action="" method="post" id="formCpptRanap">
    <div class="row">
        <div class="col-md-6 col-xl-3 col-lg-3">
            <div class="mb-1">
                <label class="form-label">No. Rawat</label>
                <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" type="text" class="form-control" name="no_rawat" readonly>
                <input value="" type="hidden" name="stts" readonly>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 col-lg-6">
            <div class="mb-1">
                <label class="form-label">Pasien</label>
                <div class="input-group mb-2">
                    <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" type="text" class="form-control" name="no_rkm_medis" readonly>
                    <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" type="text" class="form-control w-50" name="nm_pasien" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 col-lg-3">
            <div class="mb-1">
                <label class="form-label">Tgl. Lahir / Umur</label>
                <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" type="text" class="form-control" name="tgl_lahir" readonly>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 col-lg-4">
            <div class="mb-1">
                <label class="form-label">Pembiayaan</label>
                <div class="input-group mb-2">
                    <input value="-" type="text" class="form-control" name="pembiayaan" readonly>
                    <input value="-" type="text" class="form-control w-50" name="no_peserta" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 col-lg-3">
            <div class="mb-1">
                <label class="form-label">Kamar</label>
                <input value="-" type="text" class="form-control" name="kamar" readonly>
            </div>
        </div>
        <div class="col-md-6 col-xl-5 col-lg-5">
            <div class="mb-1">
                <label class="form-label text-red">Alergi</label>
                <select class="form-control" name="alergi" multiple="multiple" id="alergi" style="width:100%"></select>
            </div>
        </div>
    </div>
    <fieldset class="form-fieldset">
        <div class="row">
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Dokter/Petugas</label>
                    <select class="form-select" name="nip" id="nip" style="width: 100%"></select>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Tanggal</label>
                    <input class="form-control filterTangal" name="tgl_perawatan" id="tgl_perawatan" style="width: 100%" value="{{ date('d-m-Y') }}">
                    <input type="hidden" name="tgl_perawatan_awal" id="tgl_perawatan_awal">
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Jam</label>
                    <input type="hidden" name="jam_rawat_awal" id="jam_rawat_awal" value="" />
                    <div class="input-group">
                        <input class="form-control" name="jam_rawat" id="jam_rawat" value="{{ date('H:i:s') }}" />
                        <span class="input-group-text">
                            <input type="checkbox" name="checkJam" id="checkJam" class="form-check-input m-0" data-target="jam_rawat" />
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="mb-1">
                    <label class="form-label">Subjek</label>
                    <textarea class="form-control" rows="6" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="keluhan">-</textarea>
                </div>
            </div>
            <div class="col-xl-12 mb-2">
                <div class="mb-1">
                    <label class="form-label">Objek</label>
                    <textarea class="form-control" rows="6" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="pemeriksaan">-</textarea>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Suhu</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="suhu_tubuh">
                        <span class="input-group-text">
                            °C
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Tinggi</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="tinggi">
                        <span class="input-group-text">
                            cm
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Berat</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="berat">
                        <span class="input-group-text">
                            Kg
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Tensi</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="tensi">
                        <span class="input-group-text">
                            mmHg
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Respirasi</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="respirasi">
                        <span class="input-group-text">
                            x/mnt
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Nadi (/mnt)</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="nadi">
                        <span class="input-group-text">
                            x/mnt
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">SpO²</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="spo2">
                        <span class="input-group-text">
                            %
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">GCS</label>
                    <div class="input-group input-group-flat">
                        <input value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" onkeypress="return hanyaAngka(this)" type="text" class="form-control text-end" name="gcs">
                        <span class="input-group-text">
                            (E,V,M)
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Kesadaran</label>
                    <select class="form-select" name="kesadaran">
                        <option value="01">Compos Mentis</option>
                        <option value="02">Somnolence</option>
                        <option value="03">Sopor</option>
                        <option value="04">Coma</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">
                        Asesmen
                    </label>
                    <textarea class="form-control" rows="6" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="penilaian">-</textarea>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">
                        Instruksi
                    </label>
                    <textarea class="form-control" rows="6" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="instruksi">-</textarea>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="mb-1">
                    <label class="form-label">Plan</label>
                    <textarea class="form-control" rows="6" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="rtl">-</textarea>
                </div>
            </div>
    </fieldset>
</form>

@push('script')
    <script>
        // $(document).ready(()=>{
        //     let options = {
        //         selector: 'textarea',
        //         height: 300,
        //         menubar: false,
        //         statusbar: false,
        //         // plugins: [
        //         //     'advlist autolink lists link image charmap print preview anchor',
        //         //     'searchreplace visualblocks code fullscreen',
        //         //     'insertdatetime media table paste code help wordcount'
        //         // ],
        //         toolbar: 'undo redo | formatselect | ' +
        //             'bold italic backcolor | alignleft aligncenter ' +
        //             'alignright alignjustify | bullist numlist outdent indent | ' +
        //             'removeformat',
        //         content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
        //     }
        //     if (localStorage.getItem("tablerTheme") === 'dark') {
        //         options.skin = 'oxide-dark';
        //         options.content_css = 'dark';
        //     }
        //     tinyMCE.init(options);
        //
        // })
    </script>

@endpush
