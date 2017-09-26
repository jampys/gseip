<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('#popupbox').dialog({
                autoOpen:false
            });

            //Al cambiar el periodo
            $(document).on('change', '#periodo', function(){
                //alert('cambio el periodo');
                //preparo los parametros
                params={};
                params.periodo = $('#periodo').val();
                params.action = "objetivos";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params,function(){})

            });


            $(document).on('click', '.edit', function(){
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_objetivo = id;
                params.action = "objetivos";
                params.operation = "editObjetivo";
                $('#content').load('index.php', params,function(){
                    $('#search_panel').hide();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "objetivos";
                params.operation="newObjetivo";
                $('#content').load('index.php', params,function(){
                    $('#search_panel').hide();
                })
            });

            
            $(document).on('click', '.delete', function(){
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
                            <button  id="new" type="button" class="form-control btn btn-primary btn-sm">Nuevo objetivo</button>
                        </div>


                    </form>
                </div>


            </div>


            <div class="col-md-1"></div>

            <br/>
        </div>











        <div id="content" class="row">
            <?php include_once ($view->contentTemplate);  ?>
        </div>

    </div>

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>