

$(document).ready(function(){ //cuando el html fue cargado iniciar


    /* para el plug-in jquery validation: cuando se produce un error, agrega la clase de bootstrap has-error para pintar el borde del input  y el label de rojo */
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        }/*,
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }*/
    });


    /* para dataTables: elimina el estado de una tabla ("stateSave": true) al salir de la pantalla */
    $(document).on("click", "#myNavbar ul li a", function(){
        //$('#example').DataTable().state.clear();
        $('table.dataTable').DataTable().state.clear();
    });



    /* reglas propias para jquery validation */

    function XOR_value_validator(value, el, args) {
        var otherValue = $(args[0]).val();
        return (value && !otherValue) || (!value && otherValue);
    }

    jQuery.validator.addMethod(
        'XOR_with',
        XOR_value_validator,
        jQuery.validator.format('{1}')
    );






});

NS={};
