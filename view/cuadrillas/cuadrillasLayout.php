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
                params.search_contrato = $("#search_contrato").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "cuadrillas";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrillas";
                params.operation = "editCuadrilla";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrillas";
                params.operation = "editCuadrilla";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.empleados', function(){ //ok
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrilla-empleado";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#empleados_left_side #add').attr('id_cuadrilla', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "cuadrillas";
                params.operation="newCuadrilla";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#example .delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                var id = $(this).closest('tr').attr('data-id');
                $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
                    buttons: [
                        {
                            text: "Aceptar",
                            click: function() {
                                $.fn.borrar(id);
                            },
                            class:"btn btn-danger"
                        },
                        {
                            text: "Cancelar",
                            click: function() {
                                $(this).dialog("close");
                            },
                            class:"btn btn-default"
                        }

                    ]
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) {
                //alert(id);
                //preparo los parametros
                params={};
                params.id_habilidad_empleado = id;
                params.action = "habilidad-empleado";
                params.operation = "deleteHabilidadEmpleado";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Habilidad eliminada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidad-empleado", operation: "buscar", cuil: $("#cuil").val(), id_habilidad: $("#id_habilidad").val()});
                        $("#search").trigger("click");
                    }else{
                        $("#myElemento").html('Error al eliminar la habilidad').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');
                    }, 2000);

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

            <h4>Cuadrillas</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <label for="search_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="search_contrato" name="search_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione el contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <!--<div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="new">Nueva renovación</button>
                    </div>-->


                    <div class="form-group col-md-1">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-1">
                        <label for="search">&nbsp;</label>
                        <button type="button" style="background-color: #337ab7" class="form-control btn btn-primary btn-sm" title="nueva cuadrilla" id="new" <?php echo ( PrivilegedUser::dhasAction('CUA_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-plus fa-lg"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-7">

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