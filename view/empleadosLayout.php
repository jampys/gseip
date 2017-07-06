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


            //añado la posibilidad de editar al presionar sobre edit
            $(document).on('click', '.edit', function(){
                //this = es el elemento sobre el que se hizo click en este caso el link
                //obtengo el id que guardamos en data-id
                var id=$(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id=id;
                params.action = "clientes";
                params.operation = "editClient";
                $('#popupbox').load('index.php', params,function(){
                    $('#popupbox').dialog({title:"Editar cliente"}).dialog('open');
                })

            });





            $(document).on('click', '#new', function(){
                params={};
                params.action = "clientes";
                params.operation="newClient";
                $('#popupbox').load('index.php', params,function(){
                    $('#popupbox').dialog({title:"Nuevo cliente"}).dialog('open');
                })
            });


            $(document).on('click', '#submit',function(){
                if ($("#client").valid()){
                    var params={};
                    params.action = 'clientes';
                    params.operation = 'saveClient';
                    params.id=$('#id').val();
                    params.nombre=$('#nombre').val();
                    params.apellido=$('#apellido').val();
                    params.fecha=$('#fecha').val();
                    params.peso=$('#peso').val();
                    $.post('index.php',params,function(data, status, xhr){

                        //alert(data);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Cliente guardado con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"clientes", operation:"refreshGrid"});
                        }else{
                            $("#myElem").html('Error al guardar el cliente').addClass('alert alert-danger').show();
                        }
                        setTimeout(function() { $("#myElem").hide();
                            $('#popupbox').dialog('close');}, 2000);

                    });

                }
                return false;
            });


            // boton cancelar, uso live en lugar de bind para que tome cualquier boton
            // nuevo que pueda aparecer
            $(document).on('click', '#cancel',function(){
                $('#popupbox').dialog('close');
            });



            /*$(document).on('click', '.delete', function(){
             //obtengo el id que guardamos en data-id
             var id=$(this).attr('data-id');
             //preparo los parametros
             params={};
             params.id=id;
             params.action="deleteClient";
             $('#popupbox').load('index.php', params,function(){
             $('#content').load('index.php',{action:"refreshGrid"});
             })

             });*/


            $(document).on('click', '.delete', function(){
                //$('#confirm').dialog('open');
                $("#confirm").data('id', $(this).attr('data-id')).dialog("open");
                return false;
            });


            $.fn.borrar = function(id) {
                //alert(id);
                //preparo los parametros
                params={};
                params.id=id;
                params.action = "clientes";
                params.operation = "deleteClient";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Cliente eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"clientes", operation: "refreshGrid"});
                    }else{
                        $("#myElemento").html('Error al eliminar el cliente').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');}, 2000);

                });

            };

        });

    </script>




</head>
<body style="padding-top: 0px">
    <!--<div id ="block"></div>-->

    <?php require_once('templates/header.php'); ?>


    <div class="container">
        <br/>

        <div id="popupbox"></div>

        <div class="row">

            <div id="content" class="col-md-8 col-md-offset-2">
                <?php include_once ($view->contentTemplate); // incluyo el template que corresponda ?>
            </div>

        </div>

    </div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>