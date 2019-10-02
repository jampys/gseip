<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $(document).on('click', '#search', function(){ //ok
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.search_busqueda = $("#search_busqueda").val();
                params.search_input = $("#search_input").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "habilitas-control";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            /*$('#content').on('click', '.edit', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    $('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });*/


            /*$('#content').on('click', '.view', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });*/



            /*$(document).on('click', '#new', function(){
                params={};
                params.action = "postulaciones";
                params.operation="newPostulacion";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });*/



            /*$(document).on('click', '#example .delete', function(){
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
            });*/


            /*$.fn.borrar = function(id) {
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

            };*/

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

            <h4>Consulta de habilitas</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <label for="search_busqueda" class="control-label">Tipo de búsqueda</label>
                        <select class="form-control selectpicker show-tick" id="search_busqueda" name="search_busqueda" data-live-search="true" data-size="5">
                            <!--<option value="">Seleccione la búsqueda</option>-->
                            <option value="ot">OT Orden de trabajo</option>
                            <option value="habilita">Nro. de habilita</option>
                            <option value="certificado">Nro. de certificado</option>

                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label" for="search_input">&nbsp;</label>
                        <input class="form-control" type="text" name="search_input" id="search_input" value = "<?php //print $view->cuadrilla->getNombre() ?>" >
                    </div>

                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>


                    <div class="form-group col-md-4">

                    </div>


                    <!--<div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary" id="search">Buscar</button>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary" id="new">Nueva renovación</button>
                    </div>-->

                    <!--<div class="form-group col-md-2">
                        <label for="search_renovado" class="control-label">&nbsp;</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="search_renovado" name="search_renovado">
                                <a href="#" title="Funcionalidad en contrucción">Ver todos</a>
                            </label>
                        </div>
                    </div>-->




                    <!--<div class="form-group col-md-1">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-default" title="nueva postulación" id="new" <?php echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                        </button>
                    </div>-->



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