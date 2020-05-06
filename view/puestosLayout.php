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




            $(document).on('click', '.delete', function(){ //ok
                var id = $(this).closest('tr').attr('id_puesto');
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
                    open: function() {
                        $(this).html(confirmMessage('¿Desea eliminar el puesto de trabajo?'));
                    },
                    close: function() { $("#myElem").empty().removeClass(); }
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) { //ok
                //alert(id);
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "deletePuesto";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Puesto eliminado con exito').addClass('alert alert-success').show();
                        $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                        setTimeout(function() { $("#myElemento").hide();
                                                $('#confirm').dialog('close');
                                                $('#content').load('index.php',{action:"puestos", operation: "refreshGrid"});
                                              }, 2000);
                    }else{
                        $("#myElemento").html('No es posible eliminar el puesto').addClass('alert alert-danger').show();
                    }

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