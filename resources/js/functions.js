

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


    /* para dataTables: elimina el estado de una tabla ("stateSave": true) al salir de la pantalla */
    $(document).on("click", "#myNavbar ul li a", function(){
        //$('#example').DataTable().state.clear();
        $('table.dataTable').DataTable().state.clear();
    });






});

NS={};
