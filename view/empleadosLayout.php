<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){

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



            //al presionar el boton para ver
            $(document).on('click', '.view', function(){
                var id=$(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id=id;
                params.action = "empleados";
                params.operation = "editEmpleado";
                params.target = 'view';
                $('#content').load('index.php', params,function(){
                    //$("#empleado-form input, #empleado-form .selectpicker").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.panel-footer').css('display', 'none');
                });

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
                    params.id_convenio=$('#id_convenio').val();
                    //alert(params.cambio_domicilio);

                    $.post('index.php',params,function(data, status, xhr){
                        //No se usa .fail() porque el resultado viene de un SP y siempre devuelve 1 o -1 (no lanza excepcion PHP)
                        //alert(xhr.responseText);
                        if(data >=0){
                            $(".panel-footer button").prop("disabled", true); //deshabilito botones
                            $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                            setTimeout(function() { $("#myElem").hide();
                                                    //$('#popupbox').dialog('close');
                                                    $('#content').load('index.php',{action:"empleados", operation:"refreshGrid"});
                                                  }, 2000);

                        }else{
                            //alert(xhr.responseText);
                            $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                        }

                    }, "json");

                }
                return false;
            });



            $(document).on('click', '#cancel',function(){
                //$('#popupbox').dialog('close');
                $('#content').load('index.php',{action:"empleados", operation:"refreshGrid"});
            });

            $(document).on('click', '#back',function(){
                $("#cancel").trigger("click");
            });



            //Al presionar el boton contratos, para mostrar los contratos del empleado
            $(document).on('click', '.contratos', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_empleado = id;
                params.action = "empleados";
                params.operation = "loadContratos";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

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


    <div id="popupbox"></div>


    <?php require_once('templates/footer.php'); ?>


</body>


</html>