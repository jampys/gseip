

$(document).ready(function(){ //cuando el html fue cargado iniciar

    /************************************************ JQUERY VALIDATION ************************************************/

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


    /* regla propias para jquery validation: es un XOR logico. Debe ingresar un campo u otro, pero no ambos */
    jQuery.validator.addMethod(
        'XOR_with',
        function XOR_value_validator(value, el, args) {
            var otherValue = $(args[0]).val();
            return (value && !otherValue) || (!value && otherValue);
        },
        jQuery.validator.format('{1}')
    );


    /* regla propia para jquery validation: valida el formato del legajo XX0000
    * Da un error de javascript por consola (Uncaught TypeError: Cannot read property 'off' of null), pero no afecta al funcionamiento de la validacion
    * */

    jQuery.validator.addMethod("legajo", function(value, element) {
        //return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test( value );
        return this.optional( element ) || /^[INin || SPsp || EXex]+([0-9]{4})$/.test( value );
    }, 'Ingrese un formato de legajo correcto.');


    /**************************************************JQUERY VALIDATION ****************************************************/

    /* para dataTables: elimina el estado de una tabla ("stateSave": true) al salir de la pantalla */
    $(document).on("click", "#myNavbar ul li a", function(){
        //$('#example').DataTable().state.clear();
        $('table.dataTable').DataTable().state.clear();
    });










});

NS={};
