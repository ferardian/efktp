<div class="row gy-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <form action="" id="formPemeriksaanGigi">
            <div class="row gy-2">
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="no_rawat">No. Rawat</label>
                    <input type="text" class="form-control" id="no_rawat" name="no_rawat" readonly>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12">
                    <label for="nm_pasien">Pasien</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="no_rkm_medis" name="no_rkm_medis" readonly>
                        <input type="text" class="form-control w-50" id="nm_pasien" name="nm_pasien" readonly>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12">
                    <label for="tgl_lahir">Tgl. Lahir/Umur</label>
                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" readonly>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12">
                    <label for="dokter">Dokter</label>
                    <input type="text" class="form-control" id="dokter" name="dokter" readonly>
                    <input type="hidden" id="kd_dokter" name="kd_dokter">
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="oklusi">Oklusi</label>
                    <select class="form-select" id="oklusi" name="oklusi">
                        <option value="-" selected>-</option>
                        <option value="Normal Bite">Normal Bite</option>
                        <option value="Cross Bite">Cross Bite</option>
                        <option value="Step Bite">Step Bite</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="palatinus">Palatinus</label>
                    <select class="form-select" id="palatinus" name="palatinus">
                        <option value="-" selected>-</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                        <option value="Kecil">Kecil</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Multiple">Multiple</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="mandibularis">Mandibularis</label>
                    <select class="form-select" id="mandibularis" name="mandibularis">
                        <option value="-" selected>-</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                        <option value="Sisi Kanan">Sisi Kanan</option>
                        <option value="Sisi Kiri">Sisi Kiri</option>
                        <option value="Kedua Sisi">Kedua Sisi</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="palatum">Palatum</label>
                    <select class="form-select" id="palatum" name="palatum">
                        <option value="-" selected>-</option>
                        <option value="Dalam">Dalam</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Rendah">Rendah</option>
                    </select>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12">
                    <label for="diastema">Diastema</label>
                    <div class="input-group">
                        <select class="form-select" id="diastema" name="diastema">
                            <option value="-" selected>-</option>
                            <option value="Ada">Ada</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                        </select>
                        <input type="text" class="form-control w-50" name="ket_diastema" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12">
                    <label for="anomali">Anomali</label>
                    <div class="input-group">
                        <select class="form-select" id="anomali" name="anomali">
                            <option value="-" selected>-</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Ada">Ada</option>
                        </select>
                        <input type="text" class="form-control w-50" name="ket_anomali" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="lainnya">Lainnya</label>
                    <input type="text" class="form-control" name="lainnya" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="d">D</label>
                    <input type="text" class="form-control" name="d" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="m">M</label>
                    <input type="text" class="form-control" name="m" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                </div>
                <div class="col-xl-2 col-md-6 col-sm-12">
                    <label for="f">F</label>
                    <input type="text" class="form-control" name="f" value="-" onfocus="return removeZero(this)" onblur="isEmpty(this)">
                </div>
            </div>
        </form>
        <hr />
    </div>
    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
        <table class="tb-odonto" style="margin: 0px auto">
            <tr style="text-align: center">
                <th>28</th>
                <th>27</th>
                <th>26</th>
                <th>25</th>
                <th>24</th>
                <th>23</th>
                <th>22</th>
                <th>21</th>
                <th style="width:20px"></th>
                <th>11</th>
                <th>12</th>
                <th>13</th>
                <th>14</th>
                <th>15</th>
                <th>16</th>
                <th>17</th>
                <th>18</th>
            </tr>
            <tr>
                <td id="gigi28" class="gigi gigi_posterior" onclick="tambahPeriksaGigi('28')"></td>
                <td id="gigi27" class="gigi gigi_posterior" onclick="tambahPeriksaGigi('27')"></td>
                <td id="gigi26" class="gigi gigi_posterior" onclick="tambahPeriksaGigi('26')"></td>
                <td id="gigi25" class="gigi gigi_posterior" onclick="tambahPeriksaGigi('25')"></td>
                <td id="gigi24" class="gigi gigi_anterior" onclick="tambahPeriksaGigi('24')"></td>
                <td id="gigi23" class="gigi gigi_anterior" onclick="tambahPeriksaGigi('23')"></td>
                <td id="gigi22" class="gigi gigi_anterior" onclick="tambahPeriksaGigi('22')"></td>
                <td id="gigi21" class="gigi gigi_anterior" onclick="tambahPeriksaGigi('21')"></td>
                <th style="width:20px"></th>
                <td id="gigi11" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(11)"></td>
                <td id="gigi12" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(12)"></td>
                <td id="gigi13" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(13)"></td>
                <td id="gigi14" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(14)"></td>
                <td id="gigi15" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(15)"></td>
                <td id="gigi16" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(16)"></td>
                <td id="gigi17" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(17)"></td>
                <td id="gigi18" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(18)"></td>
            </tr>

            <tr class="mt-5" style="text-align: center">
                <th></th>
                <th></th>
                <th></th>
                <th>55</th>
                <th>54</th>
                <th>53</th>
                <th>52</th>
                <th>51</th>
                <th style="width:20px"></th>
                <th>61</th>
                <th>62</th>
                <th>63</th>
                <th>64</th>
                <th>65</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td id="gigi55" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(55)"></td>
                <td id="gigi54" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(54)"></td>
                <td id="gigi53" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(53)"></td>
                <td id="gigi52" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(52)"></td>
                <td id="gigi51" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(51)"></td>
                <th style="width:20px"></th>
                <td id="gigi61" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(61)"></td>
                <td id="gigi62" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(62)"></td>
                <td id="gigi63" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(63)"></td>
                <td id="gigi64" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(64)"></td>
                <td id="gigi65" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(65)"></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr>
                <td colspan="33" style="height: 20px"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td id="gigi85" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(85)"></td>
                <td id="gigi84" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(84)"></td>
                <td id="gigi83" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(83)"></td>
                <td id="gigi82" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(82)"></td>
                <td id="gigi81" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(81)"></td>
                <th style="width:20px"></th>
                <td id="gigi71" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(71)"></td>
                <td id="gigi72" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(72)"></td>
                <td id="gigi73" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(73)"></td>
                <td id="gigi74" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(74)"></td>
                <td id="gigi75" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(75)"></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr class="mt-5" style="text-align: center">
                <th></th>
                <th></th>
                <th></th>
                <th>85</th>
                <th>84</th>
                <th>83</th>
                <th>82</th>
                <th>81</th>
                <th style="width:20px"></th>
                <th>71</th>
                <th>72</th>
                <th>73</th>
                <th>74</th>
                <th>75</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <td id="gigi48" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(48)"></td>
                <td id="gigi47" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(47)"></td>
                <td id="gigi46" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(46)"></td>
                <td id="gigi45" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(45)"></td>
                <td id="gigi44" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(44)"></td>
                <td id="gigi43" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(43)"></td>
                <td id="gigi42" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(42)"></td>
                <td id="gigi41" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(41)"></td>
                <th style="width:20px"></th>
                <td id="gigi31" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(31)"></td>
                <td id="gigi32" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(32)"></td>
                <td id="gigi33" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(33)"></td>
                <td id="gigi34" class="gigi gigi_anterior" onclick="tambahPeriksaGigi(34)"></td>
                <td id="gigi35" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(35)"></td>
                <td id="gigi36" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(36)"></td>
                <td id="gigi37" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(37)"></td>
                <td id="gigi38" class="gigi gigi_posterior" onclick="tambahPeriksaGigi(38)"></td>
            </tr>
            <tr style="text-align: center">
                <th>48</th>
                <th>47</th>
                <th>46</th>
                <th>45</th>
                <th>44</th>
                <th>43</th>
                <th>42</th>
                <th>41</th>
                <th style="width:20px"></th>
                <th>31</th>
                <th>32</th>
                <th>33</th>
                <th>34</th>
                <th>35</th>
                <th>36</th>
                <th>37</th>
                <th>38</th>
            </tr>
        </table>
        <table class="table table-sm mt-2" widht="100%">
            <tr>
                <td colspan="6">Keterangan</td>
            </tr>
            <tr>
                <th>Simbol</th>
                <th>Keterangan</th>
                <th>Simbol</th>
                <th>Keterangan</th>
                <th>Simbol</th>
                <th>Keterangan</th>
            </tr>
            <tr>
                <td><i class="ti ti-circle-filled"></i></td>
                <td>Tumpatan</td>
                <td><i class="ti ti-arrows-horizontal"></i></td>
                <td>Erupsi</td>
                <td><i class="ti ti-x"></i></td>
                <td>Hilang</td>
            </tr>
            <tr>
                <td><i class="ti ti-letter-v"></i></td>
                <td>Sisa Akar</td>
                <td><i class="ti ti-circle"></i></td>
                <td>Karies</td>
                <td><i class="ti ti-currency-euro"></i></td>
                <td>Goyang</td>
            </tr>
        </table>
    </div>
    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 ml-4">
        <table class="table table-sm" id="tbHasilPeriksaGigi" width="100%">

        </table>
    </div>

</div>
@include('content.pemeriksaan.modal.gigi._pemeriksaanGigiHasil')
@push('script')
    <script>
        var formPemeriksaanGigi = $('#formPemeriksaanGigi')
        var formPemeriksaanGigiHasil = $('#formPemeriksaanGigiHasil')
        var selectKdPenyakit = formPemeriksaanGigiHasil.find('select[name=kd_penyakit]')
        var selectKdTindakan = formPemeriksaanGigiHasil.find('select[name=kd_tindakan]')
        var modalPemeriksaanGigiHasil = $('#modalPemeriksaanGigiHasil')
        var no_rawat = formPemeriksaanGigi.find('input[name=no_rawat]').val();

        function tambahPeriksaGigi(id) {
            cek = formPemeriksaanGigiHasil.find('input[name=posisi_gigi]').val(id)
            selectPenyakit(selectKdPenyakit, modalPemeriksaanGigiHasil)
            selectTindakan(selectKdTindakan, modalPemeriksaanGigiHasil)
            $.get(`/efktp/pemeriksaan/gigi/hasil`, {
                no_rawat: formPemeriksaanGigi.find('input[name=no_rawat]').val(),
                posisi: id,
            }).done((response) => {
                if (Object.keys(response).length) {
                    const diagnosa = new Option(`${response.kd_penyakit} - ${response.diagnosa.nm_penyakit}`, response.kd_penyakit, true, true)
                    const tindakan = new Option(`${response.kd_tindakan} - ${response.tindakan.deskripsi_pendek}`, response.kd_tindakan, true, true)
                    formPemeriksaanGigiHasil.find('select[name=hasil]').val(response.hasil).change()
                    formPemeriksaanGigiHasil.find('select[name=kd_penyakit]').append(diagnosa).trigger('change');
                    formPemeriksaanGigiHasil.find('select[name=kd_tindakan]').append(tindakan).trigger('change');
                    formPemeriksaanGigiHasil.find('textarea[name=keterangan]').val(response.keterangan)
                }
            })
            modalPemeriksaanGigiHasil.modal('show')
        }

        function loadHasilPemeriksaanGigi(no_rawat) {
            const tbHasilPeriksa = new DataTable('#tbHasilPeriksaGigi', {
                responsive: true,
                stateSave: true,
                destroy: true,
                processing: true,
                lengthChange: false,
                searching: false,
                info: false,
                ajax: {
                    url: `/efktp/pemeriksaan/gigi/hasil`,
                    data: {
                        dataTable: true,
                        no_rawat: no_rawat,
                    },
                },
                columns: [{
                        title: 'Gigi',
                        data: 'posisi_gigi',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: 'Diagnosa',
                        data: 'kd_penyakit',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: 'Tindakan',
                        data: 'kd_tindakan',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: 'Hasil',
                        data: 'hasil',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Keterangan',
                        data: 'keterangan',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: '',
                        data: 'id',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-danger btn-sm" onclick="hapusHasilPeriksaGigi('${data}')"><i class="ti ti-x"></i></button>`
                        }
                    },
                ]
            })
        }

        function hapusHasilPeriksaGigi(id) {
            const no_rawat = formPemeriksaanGigi.find('input[name=no_rawat]').val()
            Swal.fire({
                title: "Peringatan",
                html: "Yakin hapus ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Hapus",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/pemeriksaan/gigi/hasil/delete`, {
                        id: id,
                    }).done((response) => {
                        alertSuccessAjax().then(() => {
                            $('.tb-odonto tr td').html('')
                            renderHasilGigi(no_rawat);
                            loadHasilPemeriksaanGigi(no_rawat)
                        });
                    }).fail((req) => {
                        alertErrorAjax(req)
                    })
                }
            })

        }
    </script>
@endpush
