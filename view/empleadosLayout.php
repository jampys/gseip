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
                params.action = "empleados";
                params.operation = "editEmpleado";
                $('#content').load('index.php', params,function(){
                    //$('#popupbox').dialog({title:"Editar cliente"}).dialog('open');
                })

            });





            $(document).on('click', '#new', function(){
                params={};
                params.action = "empleados";
                params.operation="newEmpleado";
                $('#content').load('index.php', params,function(){
                   //$('#popupbox').dialog({title:"Nuevo empleado"}).dialog('open');
                })
            });


            $(document).on('click', '#submit',function(){
                if ($("#empleado-form").valid()){
                    var params={};
                    params.action = 'empleados';
                    params.operation = 'saveEmpleado';
                    params.id_empleado=$('#id_empleado').val();
                    params.legajo=$('#legajo').val();
                    params.apellido=$('#apellido').val();
                    params.nombre=$('#nombre').val();
                    params.documento=$('#documento').val();
                    params.cuil=$('#cuil').val();
                    params.fecha_nacimiento=$('#fecha_nacimiento').val();
                    params.fecha_alta=$('#fecha_alta').val();
                    params.fecha_baja=$('#fecha_baja').val();
                    params.direccion=$('#direccion').val();
                    params.localidad=$('#localidad').val();
                    params.telefono=$('#telefono').val();
                    params.email=$('#email').val();
                    params.sexo=$('input[name=sexo]:checked').val();
                    params.nacionalidad=$('#nacionalidad').val();
                    params.estado_civil=$('#estado_civil').val();
                    params.empresa=$('#empresa').val();
                    params.cambio_domicilio = $('#cambio_domicilio').prop('checked')? 1:0;
                    //alert(params.cambio_domicilio);

                    $.post('index.php',params,function(data, status, xhr){

                        alert(xhr.responseText);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();

                        }else{
                            $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                        }
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#popupbox').dialog('close');
                                                $('#content').load('index.php',{action:"empleados", operation:"refreshGrid"});
                                              }, 2000);

                    });

                }
                return false;
            });



            $(document).on('click', '#cancel',function(){
                //$('#popupbox').dialog('close');
                $('#content').load('index.php',{action:"empleados", operation:"refreshGrid"});
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



    <?php require_once('templates/footer.php'); ?>


</body>


</html>