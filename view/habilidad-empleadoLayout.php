<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#search', function(){


                if ($("#search_form").valid()){

                    //alert('presiono en buscar');
                    //var id = $(this).attr('data-id');
                    //preparo los parametros
                    params={};
                    params.cuil = $("#cuil").val();
                    params.id_habilidad = $("#id_habilidad").val();
                    params.action = "habilidad-empleado";
                    params.operation = "buscar";
                    //alert(params.cuil);
                    //alert(params.id_habilidad);
                    $('#content').load('index.php', params);

                }


            });



            $("#search_empleado").autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: { "term": request.term, "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.apellido+" "+item.nombre,
                                    id: item.cuil

                                };
                            }));
                        }

                    });
                },
                minLength: 2,
                change: function(event, ui) {
                    $('#cuil').val(ui.item? ui.item.id : '');
                    $('#search_empleado').val(ui.item.label);
                }
            });


            $("#search_habilidad").autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: { "term": request.term, "action":"habilidades", "operation":"autocompletarHabilidades"},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.nombre,
                                    id: item.id_habilidad

                                };
                            }));
                        },
                        error: function(data, textStatus, errorThrown) {
                            console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                        }


                    });
                },
                minLength: 2,
                change: function(event, ui) {
                    $('#id_habilidad').val(ui.item? ui.item.id : '');
                    $('#search_habilidad').val(ui.item.label);
                }
            });



            $('#search_form').validate({
                ignore:"",
                rules: {
                    cuil: {
                        required: function(item){return $('#search_empleado').val().length > 0;}
                    },
                    id_habilidad: {
                        required: function(item){return $('#search_habilidad').val().length > 0;}
                    }

                },
                messages:{
                    cuil: "Seleccione un empleado sugerido",
                    id_habilidad: "Seleccione una habilidad sugerida"
                }

            });




















            $(document).on('click', '.edit', function(){
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "editHabilidad";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });



            $(document).on('click', '#new', function(){
                params={};
                params.action = "habilidades";
                params.operation="newHabilidad";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '#submit',function(){
                if ($("#habilidad").valid()){
                    var params={};
                    params.action = 'habilidades';
                    params.operation = 'saveHabilidad';
                    params.id_habilidad=$('#id_habilidad').val();
                    params.codigo=$('#codigo').val();
                    params.nombre=$('#nombre').val();
                    params.tipo=$('#tipo').val();
                    $.post('index.php',params,function(data, status, xhr){

                        //alert(data);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Habilidad guardada con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                        }else{
                            $("#myElem").html('Error al guardar la habilidad').addClass('alert alert-danger').show();
                        }
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);

                    });

                }
                return false;
            });


            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
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
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "deleteHabilidad";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Habilidad eliminada con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"habilidades", operation: "refreshGrid"});
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

                <h4>Empleados - Habilidades</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form id="search_form" name="search_form">
                        <div class="form-group col-md-4">
                            <label for="search_empleado" class="control-label">Empleado</label>
                            <input type="text" class="form-control" id="search_empleado" placeholder="Empleado">
                            <input type="hidden" name="cuil" id="cuil"/>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <input type="text" class="form-control" id="search_habilidad" placeholder="Habilidad">
                            <input type="hidden" name="id_habilidad" id="id_habilidad"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
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