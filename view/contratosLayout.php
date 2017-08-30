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


            $(document).on('click', '.edit', function(){ //ok
                var id=$(this).attr('data-id');
                params={};
                params.id=id;
                params.action = "contratos";
                params.operation = "editContrato";
                $('#content').load('index.php', params,function(){

                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "contratos";
                params.operation="newContrato";
                $('#content').load('index.php', params,function(){

                })
            });


            $(document).on('click', '#contrato #submit',function(){ //ok
                if ($("#contrato-form").valid()){
                    var params={};
                    params.action = 'contratos';
                    params.operation = 'saveContrato';
                    params.id_contrato=$('#id_contrato').val();
                    params.nro_contrato=$('#nro_contrato').val();
                    params.fecha_desde=$('#fecha_desde').val();
                    params.fecha_hasta=$('#fecha_hasta').val();
                    params.id_responsable=$('#id_responsable').val();
                    params.id_compania=$('#compania').val();
                    //alert(params.id_compania);

                    $.post('index.php',params,function(data, status, xhr){

                        //alert(xhr.responseText);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Contrato guardado con exito').addClass('alert alert-success').show();

                        }else{
                            $("#myElem").html('Error al guardar el contrato').addClass('alert alert-danger').show();
                        }
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#popupbox').dialog('close');
                                                $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
                                              }, 2000);

                    });

                }
                return false;
            });



            $(document).on('click', '#contrato #cancel',function(){ //ok
                //$('#popupbox').dialog('close');
                $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
            });



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


            $(document).on('click', '#add-empleado', function(e){ //ok
                params={};
                params.action = "contratos";
                params.operation="addEmpleado";
                $('#popupbox1').load('index.php', params,function(){
                    $('#myModal').modal();
                    //alert('add empleado');
                });
                //e.preventDefault();
                return false;
            });








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

    <div id="popupbox1"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>