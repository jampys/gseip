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
                params.search_busqueda = $("#search_busqueda").val();
                params.search_postulante = $("#search_postulante").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "postulaciones";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){
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
            });


            $('#content').on('click', '.view', function(){
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
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.etapas', function(){
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "etapas";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_postulacion', id);
                })

            });


            $(document).on('click', '#new', function(){
                params={};
                params.action = "postulaciones";
                params.operation="newPostulacion";
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


        <div class="col-md-1"></div>

        <div class="col-md-10">

            <h4>Objetivos</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-2">
                        <label for="periodo" class="control-label">Periodo</label>
                        <select class="form-control" id="periodo" name="periodo">
                            <option value="">Todos</option>
                            <?php foreach ($view->periodos as $pe){
                                ?>
                                <option value="<?php echo $pe['periodo']; ?>"
                                    <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                                    >
                                    <?php echo $pe['periodo']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>




                    <div class="form-group col-md-8"></div>


                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button  id="new" type="button" class="form-control btn btn-primary btn-sm" title="agregar objetivo" <?php //echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?> >
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>




                </form>
            </div>


        </div>


        <div class="col-md-1"></div>

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