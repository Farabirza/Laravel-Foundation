
const domain = 'http://localhost:8000';
// const domain = 'https://cvkreatif.com';

/**
* Main Javascript
*/

$(window).resize(function() {
  if($(window).width() < 992) {
      $('.flex-remove-md').removeClass('d-flex');
  } else {
      $('.flex-remove-md').removeClass('d-flex').addClass('d-flex');
  }
});

const confirmDelete = (message) => {
  Swal.fire({
      title: 'Are you sure?',
      icon: 'info',
      text: message,
      showCancelButton: true,
      cancelButtonColor: '#666',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#d9534f',
      confirmButtonText: "Delete"
      }).then((result) => {
      if(result.isConfirmed) {
          return true;
      } else { return false; }
  });
};

// Copy to clipboard
$(document).on('click', '.btn-copy', function() {
    navigator.clipboard.writeText($(this).attr('data-copy'));
});


$(document).on('click', '.btn-warn', function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'info',
        text: $(this).attr('data-warning'),
        showCancelButton: true,
        cancelButtonColor: '#666',
        cancelButtonText: 'Batalkan',
        confirmButtonColor: '#6777ef',
        confirmButtonText: "Lanjutkan"
        }).then((result) => {
        if(!result.isConfirmed) {
            return false;
        }
        return window.location.href = url;
    })
});

$(document).ready(function(){
  // window size
  if($(window).width() < 992) {
      $('.flex-remove-md').removeClass('d-flex');
  } else {
      $('.flex-remove-md').removeClass('d-flex').addClass('d-flex');
  }
});

// -------------------- Form Handler Start -------------------- //
$('.form-handler').off('submit').submit(function(e) {
    e.preventDefault();
    $('.alert').hide().html('');
    let formName = $(this).attr('id');
    let formData = ($(this).attr('method') == "post") ? new FormData($(this)[0]) : $(this).serialize();
    let config = {
        method: $(this).attr('method'), url: domain + $(this).attr('action'), data: formData,
    };
    axios(config)
    .then((response) => {
        $('#alert-success').hide().removeClass('d-none').fadeIn('slow').append(response.data.message);
        successMessage(response.data.message);
        if(response.data.refresh == true) window.location.reload();
        if(response.data.reset == true) $(this).trigger('reset');
    })
    .catch((error) => {
        console.log(error);
        console.log(error.response);
        if(error.response.data.message) errorMessage(error.response.data.message);
        if(error.response.data) {
            validationMessage(error.response.data.errors, formName);
        }
    });
});
const validationMessage = (errorObject, formName = '') => {
    let element_id = '';
    Object.keys(errorObject).forEach(name => {
        element_id = formName != '' ? `.${formName}-alert-${name}` : `.alert-${name}`;
        $(element_id).html('');
        errorObject[name].forEach(message => {
            $(element_id).hide().removeClass('d-none').fadeIn('slow').append("<li class='list-unstyled'>"+message+"</li>");
        });
    });
};

$(".input-numeric-only").on("input", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
$(".input-limit").on("input", function() {
    let max = $(this).attr('data-max') ?? 0;
    let value = $(this).val();
    if (value.length > max) {
        value = value.substring(0, max);
        this.value = value;
    }
});
// -------------------- Form Handler End -------------------- //

const formatNumberSeparator = (input) => {
    input = String(input).replace(/-/g, '');
    if (!/^\d+$/.test(input)) {
        return input;
    }
    return input.replace(/(.{4})(?=.)/g, '$1-');
}
