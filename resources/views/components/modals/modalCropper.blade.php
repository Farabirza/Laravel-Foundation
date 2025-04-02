@push('css-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<style>
.alert-danger { font-size: .7rem; padding: .5rem; margin-top: .5rem; }
</style>
@endpush

<!-- Modal Cropper Start -->
<div class="modal fade" id="modal-cropper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- form start -->
            <form id="form-cropper-basic" class="m-0">
            <div class="modal-header flex-between">
                <h5 class="modal-title center gap-3 fw-semibold">
                    <i class='bx bx-camera'></i>
                    <span>Image Cropper</span>
                </h5>
                <button type="button" class="btn-close btn-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-dark p-0">
                <img id="cropper-image-selector" src="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <div class="center-end gap-3">
                    <button type="button" class="cropper-submit btn btn-primary center gap-2 px-4 rounded-pill"><i class='bx bx-crop' ></i>Select</button>
                </div>
            </div>
            </form>
            <!-- form end -->
        </div>
    </div>
</div>
<!-- Modal Cropper End -->

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script type="text/javascript">

// ======================== Cropperjs Start ======================== //
var cropper;
var $cropper_input = $('.cropper-input');
var cropper_type = 'basic';
var $cropper_modal = $('#modal-cropper');
var $cropper_image = document.getElementById('cropper-image-selector');
var cropper_aspect_ratio = 1;
$cropper_input.on('change', function(e) {
    let preview_element = $(this).data('cropper-preview') ?? '.cropper-preview';
    let $cropper_preview = $(`${preview_element}`);
    let base64_element = $(this).data('cropper-result') ?? '.cropper-result';
    let $cropper_base64 = $(`${base64_element}`);
    let reader = new FileReader();

    cropper_aspect_ratio = $(this).data('cropper-aspect_ratio') ?? 1;
    cropper_type = $(this).data('cropper-type') ?? 'basic';
    $cropper_modal.modal('show');
    reader.onload = (e) => {
        $('#cropper-image-selector').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);

    // On submit
    $('.cropper-submit').off('click').on('click', function(e) {
        e.preventDefault();
        switch(cropper_type) {
            default:
                if(cropper) {
                    let canvas = cropper.getCroppedCanvas();
                    let base64Image = canvas.toDataURL(); // Get image data

                    $cropper_preview.attr('src', base64Image); // Set preview image

                    // Compress the cropped image
                    compressBase64Image(base64Image, 0.7).then(compressedBase64Image => {
                        // Validation
                        const maxSizeInKB = 1024 * 5; // 5 Mb
                        let base64String = compressedBase64Image.split(',')[1];
                        let fileSizeInBytes = (base64String.length * 3) / 4;
                        let fileSizeInKB = fileSizeInBytes / 1024;

                        if(fileSizeInKB > maxSizeInKB) {
                            $('.modal').modal('hide');
                            $(this).trigger("reset");
                            return infoMessage('File size exceeds the limit of 5 MB');
                        }
                        $cropper_base64.val(compressedBase64Image);
                    });
                } else {
                    errorMessage('Image cropper failed to function');
                }
                $('.modal').modal('hide');
            break;
        }
    });
});

// Modal behaviour
$cropper_modal.on('shown.bs.modal', function () {
    cropper = new Cropper($cropper_image, {
        aspectRatio: cropper_aspect_ratio,
        viewMode: 3,
    });
}).on('hidden.bs.modal', function () {
    $cropper_input.val('');
    if(cropper) {
        cropper.destroy();
        cropper = null;
    }
});

// Compression Function
function compressBase64Image(base64Image, quality = 0.7) {
    return new Promise((resolve) => {
        const img = new Image();
        img.src = base64Image;

        img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.width = img.width; // Adjust the dimensions as needed
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            // Compress the image (quality between 0 and 1)
            const compressedBase64 = canvas.toDataURL('image/jpeg', quality);
            resolve(compressedBase64);
        };

        img.onerror = (err) => {
            console.error('Image compression failed:', err);
            resolve(base64Image); // Fallback to original image if compression fails
        };
    });
}
// ======================== Cropperjs End ======================== //

</script>
@endpush
