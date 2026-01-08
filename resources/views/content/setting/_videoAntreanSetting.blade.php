<h2 class="mb-4">Display Video Antrean</h2>
<form id="formSettingVideoAntrean" name="formSettingVideoAntrean">
    <div class="row gy-2">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <label class="col-3 col-form-label">Script Video Antrean</label>
            <textarea class="form-control" rows="6" name="txtVideoAntrean" id="txtVideoAntrean"> </textarea>
            <button type="button" class="btn btn-success mt-2" id="btnVideoAntrean" onclick="setVideoAntrean()"><i class="ti ti-device-floppy"></i> Simpan</button>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div id="contentVideoAntrean" style="height:50vh"></div>
        </div>
    </div>
</form>
@push('script')
    <script>
        const formSettingVideoAntrean = $('#formSettingVideoAntrean');
        const contentVideoAntrean = $('#contentVideoAntrean');

        function setVideoAntrean() {
            const content = $("#txtVideoAntrean").val();
            $.post(`/efktp/setting/antrean/video`, {
                title: 'video',
                content: content
            }).done((response) => {
                alertSuccessAjax('Berhasil set video antrean')
                contentVideoAntrean.html(response.content);
                $("#txtVideoAntrean").addClass('is-valid')
                $('iframe').attr('width', '100%').attr('height', '100%')

            })
        }

        function getVideoAntrean() {
            $.get(`/efktp/setting/antrean/video`).done((response) => {
                const content = response.content
                contentVideoAntrean.html(content);
                $("#txtVideoAntrean").val(content)
                $('iframe').attr('width', '100%').attr('height', '100%')
            })
        }
    </script>
@endpush
