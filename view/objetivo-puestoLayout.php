<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#search', function(){

                if ($("#search_form").valid()){ //ok
                    //alert('presiono en buscar');
                    //var id = $(this).attr('data-id');
                    params={};
                    params.id_puesto = $("#id_puesto").val();
                    params.id_habilidad = $("#id_habilidad").val();
                    params.periodo = $("#periodo").val();
                    params.action = "habilidad-puesto";
                    params.operation = "buscar";
                    //alert(params.cuil);
                    //alert(params.id_habilidad);
                    $('#content').load('index.php', params);
                }

            });



            $("#search_puesto").autocomplete({ //ok
                source: function( request, response ) {
                    $.ajax({
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: { "term": request.term, "action":"puestos", "operation":"autocompletarPuestos"},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.nombre,
                                    id: item.id_puesto

                                };
                            }));
                        }

                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $('#id_puesto').val(ui.item? ui.item.id : '');
                    $('#search_puesto').val(ui.item.label);
                },
                search: function(event, ui) { $('#id_puesto').val(''); }
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
                select: function(event, ui) {
                    $('#id_habilidad').val(ui.item? ui.item.id : '');
                    $('#search_habilidad').val(ui.item.label);
                },
                search: function(event, ui) { $('#id_habilidad').val(''); }
            });



            $('#search_form').validate({
                ignore:"",
                rules: {
                    search_puesto: {
                        require_from_group: {
                            param: [2, ".puesto-group"],
                            depends: function(element) { return $('#search_puesto').val().length > 0;}
                        }
                    },
                    search_habilidad: {
                        require_from_group: {
                            param: [2, ".habilidad-group"],
                            depends: function(element) { return $('#search_habilidad').val().length > 0;}
                        }
                    }

                },
                messages:{
                    search_puesto: "Seleccione un puesto sugerido",
                    search_habilidad: "Seleccione una habilidad sugerida"
                }

            });



            $(document).on('click', '.edit', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_habilidad_puesto = id;
                params.action = "habilidad-puesto";
                params.operation = "editHabilidadPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModalUpdate').modal();
                })

            });



            $(document).on('click', '#new', function(){
                params={};
                params.action = "habilidad-puesto";
                params.operation="new";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


         
            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });



            $(document).on('click', '#example .delete', function(){
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
                params.id_habilidad_puesto = id;
                params.action = "habilidad-puesto";
                params.operation = "deleteHabilidadPuesto";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Habilidad eliminada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidad-puesto", operation: "buscar", id_puesto: $("#id_puesto").val(), id_habilidad: $("#id_habilidad").val()});
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

                <h4>Objetivos - Puestos</h4>
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

                        <div class="form-group col-md-4">
                            <label for="search_puesto" class="control-label">Puesto</label>
                            <input type="text" class="form-control puesto-group" id="search_puesto" name="search_puesto" placeholder="Puesto">
                            <input type="hidden" name="id_puesto" id="id_puesto" class="puesto-group"/>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <input type="text" class="form-control habilidad-group" id="search_habilidad" name="search_habilidad" placeholder="Habilidad">
                            <input type="hidden" name="id_habilidad" id="id_habilidad" class="habilidad-group"/>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="new">
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