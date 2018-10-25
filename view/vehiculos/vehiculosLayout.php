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
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "editVehiculo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });

            $(document).on('click', '.view', function(){ //ok
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "editVehiculo";
                $('#popupbox').load('index.php', params,function(){
                    $("#vehiculo-form input, #vehiculo-form .selectpicker, #vehiculo-form textarea").prop("disabled", true);
                    $('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });





            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "vehiculos";
                params.operation="newVehiculo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '#submit',function(){ //ok
                if ($("#vehiculo-form").valid()){
                    var params={};
                    params.action = 'vehiculos';
                    params.operation = 'saveVehiculo';
                    params.id_vehiculo = $('#id_vehiculo').val();
                    params.nro_movil = $('#nro_movil').val();
                    params.matricula = $('#matricula').val();
                    params.marca = $('#marca').val();
                    params.modelo = $('#modelo').val();
                    params.modelo_ano = $('#modelo_ano').val();
                    params.tetra = $('#tetra').val();
                    params.propietario = $('#propietario').val();
                    params.leasing = $('#leasing').val();
                    params.fecha_baja = $('#fecha_baja').val();
                    params.responsable = $('#responsable').val();
                    //alert(params.responsable);
                    $.post('index.php',params,function(data, status, xhr){

                        //alert(data);
                        //var rta= parseInt(data.charAt(3));
                        //alert(rta);
                        if(data >=0){
                            $(".modal-footer button").prop("disabled", true); //deshabilito botones
                            $("#myElem").html('Vehículo guardado con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"vehiculos", operation:"refreshGrid"});
                            setTimeout(function() { $("#myElem").hide();
                                                    $('#myModal').modal('hide');
                                                  }, 2000);
                        }else{
                            $("#myElem").html('Error al guardar el vehículo').addClass('alert alert-danger').show();
                        }


                    }, "json");


                }
                return false;
            });


            $(document).on('click', '#cancel',function(){
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

                    ],
                    close: function() { $("#myElem").empty().removeClass(); }
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) { //ok
                //alert(id);
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "deleteVehiculo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElem").html('Vehículo eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"vehiculos", operation: "refreshGrid"});
                        $('.btn').attr("disabled", true); //deshabilito botones
                        setTimeout(function() { $("#myElem").hide();
                                                $('#confirm').dialog('close');
                                              }, 2000);
                    }else{
                        $("#myElem").html('No es posible eliminar el vehículo').addClass('alert alert-danger').show();
                    }

                });

            };


            //Al presionar el boton contratos, para mostrar los contratos del empleado
            $(document).on('click', '.contratos', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
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