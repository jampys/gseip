<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_grupo = id;
                params.action = "vto_gruposVehiculos";
                params.operation = "editGrupo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_grupo = id;
                params.action = "vto_gruposVehiculos";
                params.operation = "editGrupo";
                $('#popupbox').load('index.php', params,function(){
                    $("#grupo-form input, #grupo-form .selectpicker, #grupo-form textarea").prop("disabled", true);
                    $('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '.etapas', function(){
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "etapas";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_postulacion', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "vto_gruposVehiculos";
                params.operation="newGrupo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
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

                    ],
                    close: function() { $("#myElem").empty().removeClass(); }
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) {
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
                        $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                        setTimeout(function() { $("#myElem").hide();
                                                $('#confirm').dialog('close');
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('No es posible eliminar el vehículo').addClass('alert alert-danger').show();
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