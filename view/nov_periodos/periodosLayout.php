<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $(document).on('click', '#search', function(){
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.search_puesto = $("#search_puesto").val();
                params.search_localidad = $("#search_localidad").val();
                params.search_contrato = $("#search_contrato").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "busquedas";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "editBusqueda";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    //$('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });


            $('#content').on('click', '.view', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "editBusqueda";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            //Al presionar el boton detalles....
            $('#content').on('click', '.detalles', function(){
                //alert('tocó en contratos');
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_busqueda = id;
                params.action = "postulaciones2";
                params.operation = "loadDetalles";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_busqueda', id);
                })

            });


            $(document).on('click', '#new', function(){
                params={};
                params.action = "busquedas";
                params.operation="newBusqueda";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });




            var dialog;
            $(document).on('click', '#example .delete', function(){
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la búsqueda?</p>",
                    size: 'small',
                    buttons: {
                        cancel: {
                            label: "No"
                        },
                        ok: {
                            label: "Si",
                            className: 'btn-danger',
                            callback: function(){
                                $.fn.borrar(id);
                                return false; //evita que se cierre automaticamente
                            }
                        }
                    }
                });


            });



            $.fn.borrar = function(id) {
                //alert(id);
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "deleteBusqueda";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Búsqueda eliminada con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la búsqueda</div>');

                });

            };



        });

    </script>




</head>
<body>


<?php require_once('templates/header.php'); ?>


<div class="container">




    <br/>
    <div class="row">


        <!--<div class="col-md-1"></div>-->

        <div class="col-md-12">

            <h4>Períodos de Liquidación</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <!--<label for="search_puesto" class="control-label">Puesto</label>-->
                        <select id="search_puesto" name="search_puesto" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un puesto</option>
                            <?php foreach ($view->puestos as $pue){
                                ?>
                                <option value="<?php echo $pue['id_puesto']; ?>">
                                    <?php echo $pue['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>



                    <div class="form-group col-md-3">
                        <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                        <select class="form-control selectpicker show-tick" id="search_contrato" name="search_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-2">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-4">

                    </div>


                </form>
            </div>


        </div>


        <!--<div class="col-md-1"></div>-->

    </div>
    <br/>









    <div id="content" class="row">
        <?php include_once ($view->contentTemplate);  ?>
    </div>

</div>

<div id="popupbox"></div>



<?php require_once('templates/footer.php'); ?>


</body>


</html>