<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '.edit', function(){
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "editHabilidad";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });

            $(document).on('click', '.view', function(){
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "editHabilidad";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("#habilidad input, #habilidad .selectpicker, #habilidad textarea").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#new', function(){
                params={};
                params.action = "habilidades";
                params.operation="newHabilidad";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            $(document).on('click', '#submit',function(){
                if ($("#habilidad").valid()){
                    var params={};
                    params.action = 'habilidades';
                    params.operation = 'saveHabilidad';
                    params.id_habilidad=$('#id_habilidad').val();
                    params.codigo=$('#codigo').val();
                    params.nombre=$('#nombre').val();

                    $.post('index.php',params,function(data, status, xhr){
                        if(data >=0){
                            $(".modal-footer button").prop("disabled", true); //deshabilito botones
                            $("#myElem").html('Habilidad guardada con exito').addClass('alert alert-success').show();
                            $('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                            setTimeout(function() { $("#myElem").hide();
                                                    $('#myModal').modal('hide');
                                                  }, 2000);
                        }

                    }, "json").fail(function(jqXHR, textStatus, errorThrown ) {
                        //alert('Entro a fail '+jqXHR.responseText);
                        $("#myElem").html('Error al guardar la habilidad').addClass('alert alert-danger').show();
                    });

                }
                return false;
            });


            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });



            var dialog;
            $(document).on('click', '.delete', function(){

                var id = $(this).attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la habilidad?</p>",
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
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Habilidad eliminada con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $('#content').load('index.php',{action:"habilidades", operation: "refreshGrid"});
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la habilidad</div>');

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