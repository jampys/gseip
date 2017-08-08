<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#search', function(){
                alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                //params={};
                //params.id_habilidad = id;
                //params.action = "habilidades";
                //params.operation = "editHabilidad";
                $('#content').load('index.php',{action:"empleado-habilidad", operation:"refreshGrid"});

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


            <div class="col-md-2"></div>
            
            <div class="col-md-8">

                <h4>Empleados - Habilidades</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form>
                        <div class="form-group col-md-4">
                            <label for="search_empleado" class="control-label">Empleado</label>
                            <input type="text" class="form-control" id="search_empleado" placeholder="Empleado">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <input type="text" class="form-control" id="search_habilidad" placeholder="Habilidad">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                        </div>
                    </form>
                </div>


            </div>


            <div class="col-md-2"></div>

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