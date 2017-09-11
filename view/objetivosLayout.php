<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_objetivo = id;
                params.action = "objetivos";
                params.operation = "editObjetivo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "objetivos";
                params.operation="newObjetivo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '#submit',function(){ //ok
                //if ($("#objetivo").valid()){
                    var params={};
                    params.action = 'objetivos';
                    params.operation = 'saveObjetivo';
                    params.id_objetivo=$('#id_objetivo').val();
                    params.nombre=$('#nombre').val();
                    params.tipo=$('#tipo').val();
                    params.objetivo_superior=$('#superior').val();
                    //alert(params.codigo_superior);
                    $.post('index.php',params,function(data, status, xhr){

                        //alert(data);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Objetivo guardado con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"objetivos", operation:"refreshGrid"});
                        }else{
                            $("#myElem").html('Error al guardar el objetivo').addClass('alert alert-danger').show();
                        }
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);

                    });

                //}
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
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "deletePuesto";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Puesto eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"puestos", operation: "refreshGrid"});
                    }else{
                        $("#myElemento").html('Error al eliminar el puesto').addClass('alert alert-danger').show();
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

        <div id="content" class="row">
            <?php include_once ($view->contentTemplate);  ?>
        </div>

    </div>

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>