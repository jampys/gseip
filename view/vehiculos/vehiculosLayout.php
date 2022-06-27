<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "editVehiculo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;

            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "editVehiculo";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("#vehiculo-form input, #vehiculo-form .selectpicker, #vehiculo-form textarea").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                });
                return false;

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "vehiculos";
                params.operation="newVehiculo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $('#myModal').on('click', '#submit',function(){ //ok
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
                            //$('#content').load('index.php',{action:"vehiculos", operation:"refreshGrid"});
                            setTimeout(function() { $("#myElem").hide();
                                                    $('#myModal').modal('hide');
                                                    $('#example').DataTable().ajax.reload(null, false);
                                                  }, 2000);
                        }

                    }, "json").fail(function(jqXHR, textStatus, errorThrown ) {
                        //alert('Entro a fail '+jqXHR.responseText);
                        $("#myElem").html('Error al guardar el vehículo').addClass('alert alert-danger').show();
                    });


                }
                return false;
            });


            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });




            var dialog;
            $('#content').on('click', '.delete', function(){

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el vehículo?</p>",
                    size: 'small',
                    buttons: {
                        cancel: {
                            label: "No"
                        },
                        ok: {
                            label: "Si",
                            className: 'btn-danger',
                            callback: function(){
                                $.fn.borrar(id);
                                return false; //evita que se cierre automaticamente
                            }
                        }
                    }
                });
                return false;

            });



            $.fn.borrar = function(id) {
                //alert(id);
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "deleteVehiculo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Vehículo eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $('#content').load('index.php',{action:"vehiculos", operation: "refreshGrid"});
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el vehículo</div>');

                });

            };


            //Al presionar el boton contratos, para mostrar los contratos del empleado
            $('#content').on('click', '.contratos', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_vehiculo = id;
                params.action = "vehiculos";
                params.operation = "loadContratos";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
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

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>