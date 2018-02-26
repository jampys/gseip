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
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "editPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "puestos";
                params.operation="newPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '#submit',function(){ //ok
                if ($("#puesto").valid()){
                    var params={};
                    params.action = 'puestos';
                    params.operation = 'savePuesto';
                    params.id_puesto=$('#id_puesto').val();
                    params.nombre=$('#nombre').val();
                    params.descripcion=$('#descripcion').val();
                    params.codigo=$('#codigo').val();
                    params.id_puesto_superior=$('#id_puesto_superior').val();
                    params.id_area=$('#id_area').val();
                    params.id_nivel_competencia=$('#id_nivel_competencia').val();
                    //alert(params.id_puesto_superior);
                    $.post('index.php',params,function(data, status, xhr){

                        //alert(data);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Puesto guardado con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"puestos", operation:"refreshGrid"});
                            setTimeout(function() { $("#myElem").hide();
                                $('#myModal').modal('hide');
                            }, 2000);
                        }else{
                            $("#myElem").html('Error al guardar el puesto').addClass('alert alert-danger').show();
                        }


                    }, "json");


                }
                return false;
            });


            $(document).on('click', '#cancel',function(){ //ok
                $('#myModal').modal('hide');
            });




            $(document).on('click', '.delete', function(){ //ok
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


            $.fn.borrar = function(id) { //ok
                //alert(id);
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "deletePuesto";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElem").html('Puesto eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"puestos", operation: "refreshGrid"});
                        setTimeout(function() { $("#myElem").hide();
                            $('#confirm').dialog('close');
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al eliminar el puesto').addClass('alert alert-danger').show();
                    }

                }, "json");

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