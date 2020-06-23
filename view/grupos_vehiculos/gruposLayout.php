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
                params.id_grupo = id;
                params.action = "vto_gruposVehiculos";
                params.operation = "editGrupo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_grupo = id;
                params.action = "vto_gruposVehiculos";
                params.operation = "editGrupo";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("#grupo-form input, #grupo-form .selectpicker, #grupo-form textarea").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })
            });


            $('#content').on('click', '.vehiculos', function(){ //ok
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_grupo = id;
                params.action = "vto_grupo-vehiculo";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_grupo', id);
                })

            });


            $('#content').on('click', '#new', function(){ //ok
                params={};
                params.action = "vto_gruposVehiculos";
                params.operation="newGrupo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            var dialog;
            $('#content').on('click', '.delete', function(){

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el grupo?</p>",
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


            });



            $.fn.borrar = function(id) {
                //alert(id);
                params={};
                params.id_grupo = id;
                params.action = "vto_gruposVehiculos";
                params.operation = "deleteGrupo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Grupo eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $('#content').load('index.php',{action:"vto_gruposVehiculos", operation: "refreshGrid"});
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el grupo</div>');

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