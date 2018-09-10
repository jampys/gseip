<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            //Al cambiar el periodo
            //$('#search_panel').on('change', '#periodo', function(){ //ok
            $(document).on('click', '#search', function(){ //ok
                //alert('cambio el periodo');
                params={};
                params.periodo = $('#periodo').val();
                params.cerrado = $('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "refreshGrid";
                //alert(params.cerrado);
                $('#content').load('index.php', params,function(){})

            });


            //Al presionar en editar una evaluacion de competencias
            $(document).on('click', '.loadEac', function(){ //ok
                params={};
                //params.id_evaluacion_competencia = $(this).closest('tr').attr('id_evaluacion_competencia');
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.id_plan_evaluacion = $(this).closest('tr').attr('id_plan_evaluacion');
                params.periodo = $(this).closest('tr').attr('periodo'); //$('#periodo').val();
                params.cerrado = $(this).closest('tr').attr('cerrado'); //$('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "loadEac";
                //alert(params.cerrado);
                $('#popupbox').load('index.php', params,function(){
                    $('#modalEac').modal();
                });
                //$('#popupbox').data({'id_empleado':params.id_empleado, 'id_plan_evaluacion': params.id_plan_evaluacion}); //paso parametros

            });

            //Al presionar en editar una evaluacion de objetivos
            $(document).on('click', '.loadEao', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
            });



            $(document).on('click', '.delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                var id = $(this).attr('data-id');
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
                params.id_objetivo = id;
                params.action = "objetivos";
                params.operation = "deleteObjetivo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Objetivo eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"objetivos", operation: "refreshGrid"});
                        $('.btn').attr("disabled", true); //deshabilito botones
                    }else{
                        $("#myElemento").html('Error al eliminar el objetivo').addClass('alert alert-danger').show();
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






        <div class="row" id="search_panel">
            <br/>


            <div class="col-md-1"></div>

            <div class="col-md-10">

                <h4>Evaluación anual de desempeño</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form id="search_form" name="search_form">

                        <div class="form-group col-md-2">
                            <label for="periodo" class="control-label">Plan evaluación</label>

                            <select class="form-control" id="periodo" name="periodo">
                                <?php foreach ($view->periodos as $pe){
                                    ?>
                                    <option value="<?php echo $pe['periodo']; ?>"
                                            cerrado="<?php echo $pe['cerrado']; ?>"
                                            <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                                        >
                                        <?php echo $pe['periodo']; ?>
                                    </option>
                                <?php  } ?>
                            </select>

                        </div>

                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>


                        <div class="form-group col-md-8"></div>



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