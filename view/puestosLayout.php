<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('id_puesto');
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "editPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });

            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('id_puesto');
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "editPuesto";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("#puesto input, #puesto .selectpicker, #puesto textarea").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            //Al presionar el boton detalles....
            $(document).on('click', '.detalles', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).closest('tr').attr('id_puesto');
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "loadDetalles";
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




            var dialog;
            $(document).on('click', '.delete', function(){

                var id = $(this).attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el puesto de trabajo?</p>",
                    size: 'small',
                    centerVertical: true,
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
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "deleteHabilidad";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Puesto eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $('#content').load('index.php',{action:"puestos", operation: "refreshGrid"});
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el puesto</div>');

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