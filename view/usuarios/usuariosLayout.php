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
                params.id_user = id;
                params.action = "sec_users";
                params.operation = "editUsuario";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;

            });

            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_user = id;
                params.action = "sec_users";
                params.operation = "editUsuario";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                });
                return false;

            });


            $('#content').on('click', '.roles', function(){ //ok
                //alert('presiono sobre roles');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_user = id;
                params.action = "sec_user-role";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side').attr('id_user', id);
                });
                return false;

            });



            $('#content').on('click', '#new', function(){ //ok
                params={};
                params.action = "sec_users";
                params.operation="newUsuario";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;

            });




            var dialog;
            $('#content').on('click', '.delete', function(){

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el usuario?</p>",
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
                params.id_user = id;
                params.action = "sec_users";
                params.operation = "deleteUsuario";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Usuario eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $('#content').load('index.php',{action:"sec_users", operation: "refreshGrid"});
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el usuario</div>');

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