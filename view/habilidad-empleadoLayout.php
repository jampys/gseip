<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#search', function(){ //ok

                if ($("#search_form").valid()){ //ok
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



            $("#search_empleado").autocomplete({ //ok
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
                select: function(event, ui) {
                    $('#cuil').val(ui.item? ui.item.id : '');
                    $('#search_empleado').val(ui.item.label);
                },
                search: function(event, ui) { $('#cuil').val(''); }
            });


            $("#search_habilidad").autocomplete({ //ok
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
                select: function(event, ui) {
                    $('#id_habilidad').val(ui.item? ui.item.id : '');
                    $('#search_habilidad').val(ui.item.label);
                },
                search: function(event, ui) { $('#id_habilidad').val(''); }
            });



            $('#search_form').validate({ //ok
                ignore:"",
                rules: {
                    search_empleado: {
                        //digits: function(item){return ($('#search_empleado').val().length > 0 && $('#cuil').val().length == 0); }
                        require_from_group: {
                            param: [2, ".empleado-group"],
                            depends: function(element) { return $('#search_empleado').val().length > 0;}
                        }
                    },
                    search_habilidad: {
                        //required: function(item){return $('#search_habilidad').val().length > 0;}
                        require_from_group: {
                            param: [2, ".habilidad-group"],
                            depends: function(element) { return $('#search_habilidad').val().length > 0;}
                        }
                    }

                },
                messages:{
                    search_empleado: "Seleccione un empleado sugerido",
                    search_habilidad: "Seleccione una habilidad sugerida"
                }

            });



            $(document).on('click', '.edit', function(){
                alert('Funcionalidad en desarrollo');
                var id = $(this).attr('data-id');
                params={};
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "editHabilidad";
                /*$('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })*/

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "habilidad-empleado";
                params.operation="new";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


         
            $(document).on('click', '#cancel',function(){ //ok
                $('#myModal').modal('hide');
            });




            $(document).on('click', '#example .delete', function(){ //ok
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


            $.fn.borrar = function(id) { //ok
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

                <h4>Habilidades - Empleados</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form id="search_form" name="search_form">
                        <div class="form-group col-md-4">
                            <label for="search_empleado" class="control-label">Empleado</label>
                            <input type="text" class="form-control empleado-group" id="search_empleado" name="search_empleado" placeholder="Empleado">
                            <input type="hidden" name="cuil" id="cuil" class="empleado-group"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <input type="text" class="form-control habilidad-group" id="search_habilidad" name="search_habilidad" placeholder="Habilidad">
                            <input type="hidden" name="id_habilidad" id="id_habilidad" class="habilidad-group"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="new">Agregar</button>
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