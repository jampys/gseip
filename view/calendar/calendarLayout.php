<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker({
                //propiedades del selectpicker

            }).change(function(){
                $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                                 // elimine el mensaje de requerido de jquery validation
            });





            //Select dependiente: al seleccionar contrato carga periodos vigentes
            $('#add-form').on('change', '#add_contrato', function(e){
                //alert('seleccionó un contrato');
                //throw new Error();
                params={};
                params.action = "partes2";
                params.operation = "getPeriodosAndCuadrillas";
                //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
                params.id_contrato = $('#add_contrato').val();
                //params.activos = 1;

                $('#id_periodo').empty();
                $('#cuadrilla').empty();


                $.ajax({
                    url:"index.php",
                    type:"post",
                    //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        //$("#id_periodo").html('<option value="">Seleccione un período</option>');
                        if(Object.keys(data['periodos']).length > 0){
                            $.each(data['periodos'], function(indice, val){
                                var label = data['periodos'][indice]["nombre"]+' ('+data['periodos'][indice]["fecha_desde"]+' - '+data['periodos'][indice]["fecha_hasta"]+')';
                                $("#id_periodo").append('<option value="'+data['periodos'][indice]["id_periodo"]+'"'
                                                        +' fecha_desde="'+data['periodos'][indice]["fecha_desde"]+'"'
                                                        +' fecha_hasta="'+data['periodos'][indice]["fecha_hasta"]+'"'
                                +'>'+label+'</option>');
                            });

                            //si es una edicion o view, selecciona el concepto.
                            //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        }

                        $("#cuadrilla").html('<option value="">Seleccione una cuadrilla</option>');
                        if(Object.keys(data['cuadrillas']).length > 0){
                            $.each(data['cuadrillas'], function(indice, val){
                                var label = data['cuadrillas'][indice]["nombre"];
                                $("#cuadrilla").append('<option value="'+data['cuadrillas'][indice]["nombre"]+'"'
                                //+' fecha_desde="'+data['periodos'][indice]["fecha_desde"]+'"'
                                //+' fecha_hasta="'+data['periodos'][indice]["fecha_hasta"]+'"'
                                +'>'+label+'</option>');
                            });

                            //si es una edicion o view, selecciona el concepto.
                            //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        }

                        $('#id_periodo').selectpicker('refresh');
                        $('#cuadrilla').selectpicker('refresh');
                        $('#add_fecha').val('');

                    },
                    error: function(data, textStatus, errorThrown) {
                        //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                        alert(data.responseText);
                    }

                });


            });





            $('#add-form').validate({
                rules: {
                    add_fecha: {required: true},
                    add_contrato: {required: true},
                    id_periodo: {required: true}
                },
                messages:{
                    add_fecha: "Seleccione la fecha",
                    add_contrato: "Seleccione el contrato",
                    id_periodo: "Seleccione el período"
                }

            });



        });

    </script>




</head>
<body>


<?php require_once('templates/header.php'); ?>



<div class="container">

    <div id="content" class="row">
        <?php include_once ($view->contentTemplate);  ?>
    </div>

</div>


<div id="popupbox"></div>




<?php require_once('templates/footer.php'); ?>


</body>


</html>