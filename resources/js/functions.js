

$(document).ready(function(){ //cuando el html fue cargado iniciar


    $.validator.setDefaults({
        highlight: function(element) {
            //$(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });





});

NS={};
