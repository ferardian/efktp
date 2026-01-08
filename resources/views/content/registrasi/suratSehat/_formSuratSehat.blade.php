 <form action="" id="formSuratSehat">
     <div class="row p-3 gy-2">
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="tanggalawal">Tanggal</label>
             <input class="form-control filterTangal" name="tanggalsurat" id="tanggalsurat" value="{{ date('d-m-Y') }}" />
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="no_surat">Nomor Surat</label>
             <input type="text" class="form-control" name="no_surat" id="no_surat" readonly />
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="no_rawat">No Rawat</label>
             <input type="text" class="form-control" name="no_rawat" id="no_rawat" readonly />
         </div>
         <div class="col-xl-4 col-md-6 col-sm-12">
             <label for="pasien">Pasien</label>
             <input type="text" class="form-control" name="pasien" id="pasien" readonly />
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="tgl_lahir">Tgl. Lahir</label>
             <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" readonly />
         </div>
         <div class="col-xl-4 col-md-6 col-sm-12">
             <label for="pasien">Alamat</label>
             <input type="text" class="form-control" name="alamat" id="alamat" readonly />
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="berat">Berat Badan</label>
             <div class="input-group">
                 <input type="text" onfocus="return removeZero(this)" onblur="isEmpty(this)" class="form-control text-end" name="berat" id="berat" value="-" />
                 <span class="input-group-text">Kg</span>
             </div>
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="tinggi">Tinggi</label>
             <div class="input-group">
                 <input type="text" onfocus="return removeZero(this)" onblur="isEmpty(this)" class="form-control text-end" name="tinggi" id="tinggi" value="-" />
                 <span class="input-group-text">cm</span>
             </div>
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="tensi">Tensi</label>
             <div class="input-group">
                 <input type="text" onfocus="return removeZero(this)" onblur="isEmpty(this)" class="form-control text-end" name="tensi" id="tensi" value="-" />
                 <span class="input-group-text">mmHG</span>
             </div>
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="suhu">Suhu</label>
             <div class="input-group">
                 <input type="text" onfocus="return removeZero(this)" onblur="isEmpty(this)" class="form-control text-end" name="suhu" id="suhu" value="-" />
                 <span class="input-group-text">°C</span>
             </div>
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="butawarna">Buta Warna</label>
             <select class="form-select" name="butawarna" id="butawarna">
                 <option value="Tidak" selected>Tidak</option>
                 <option value="Ya">Ya</option>
             </select>
         </div>
         <div class="col-xl-3 col-md-6 col-sm-12">
             <label for="keperluan">Keperluan</label>
             <input type="text" onfocus="return removeZero(this)" onblur="isEmpty(this)" class="form-control" name="keperluan" id="keperluan" value="-" />
         </div>
         <div class="col-xl-2 col-md-6 col-sm-12">
             <label for="kesimpulan">Kesimpulan</label>
             <select class="form-select" name="kesimpulan" id="kesimpulan">
                 <option value="Sehat" selected>Sehat</option>
                 <option value="Tidak Sehat">Tidak Sehat</option>
             </select>
         </div>
     </div>
 </form>
