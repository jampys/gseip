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

                if ($("#search_form").valid()){ //ok
                    //alert('presiono en buscar');
                    //var id = $(this).attr('data-id');
                    params={};
                    params.id_puesto = $("#search_puesto").val();
                    params.id_habilidad = $("#search_habilidad").val();
                    params.action = "habilidad-puesto";
                    params.operation = "buscar";
                    //alert(params.cuil);
                    //alert(params.id_habilidad);
                    $('#content').load('index.php', params);
                }

            });



            $('#search_puesto').closest('.form-group').find(':input').on('keyup', function(e){ //ok
                //alert('hola');
                var code = (e.keyCode || e.which);
                if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                    return;
                }

                var items="";

                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": $(this).val(),  "action":"puestos", "operation":"autocompletarPuestos"},
                    success: function(data) {
                        $.each(data.slice(0, 5),function(index,item)
                        {
                            //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                            items+="<option value='"+item['id_puesto']+"'>"+item['nombre']+"</option>";
                        });

                        $("#search_puesto").html(items);
                        $('.selectpicker').selectpicker('refresh');
                    }

                });

            });



            $('#search_habilidad').closest('.form-group').find(':input').on('keyup', function(e){ //ok
                //alert('hola');
                var code = (e.keyCode || e.which);
                if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                    return;
                }

                var items="";

                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": $(this).val(),  "action":"habilidades", "operation":"autocompletarHabilidades"},
                    success: function(data) {
                        $.each(data.slice(0, 5),function(index,item)
                        {
                            //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                            items+="<option value='"+item['id_habilidad']+"'>"+item['nombre']+"</option>";
                        });

                        $("#search_habilidad").html(items);
                        $('.selectpicker').selectpicker('refresh');
                    }

                });

            });



            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_habilidad_puesto = id;
                params.action = "habilidad-puesto";
                params.operation = "editHabilidadPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModalUpdate').modal();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "habilidad-puesto";
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

                <h4>Habilidades por puesto</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form id="search_form" name="search_form">

                        <div class="form-group col-md-4">
                            <label for="search_puesto" class="control-label">Puesto</label>
                            <select id="search_puesto" name="search_puesto" class="form-control selectpicker" data-live-search="true" title="Seleccione un puesto">
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <select id="search_habilidad" name="search_habilidad" class="form-control selectpicker" data-live-search="true" title="Seleccione una habilidad">
                            </select>
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