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
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "editPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });

            $(document).on('click', '.view', function(){
                var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_puesto = id;
                params.action = "puestos";
                params.operation = "editPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $("#puesto input, #puesto .selectpicker, #puesto textarea").prop("disabled", true);
                    $('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
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

                    ]
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
                        $("#myElem").html('Puesto eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"puestos", operation: "refreshGrid"});
                        $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                        setTimeout(function() { $("#myElem").hide();
                                                $('#confirm').dialog('close');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al eliminar el puesto').addClass('alert alert-danger').show();
                    }

                }, "json");

            };

        });

    </script>




</head>
<body>


    <?php require_once('templates/header.php'); ?>


    <div class="container">

        <div id="content" class="row">
            <?php //include_once ($view->contentTemplate);  ?>


            <br/>
            <!--https://www.youtube.com/watch?v=5-sIoU632dE-->
            <p><a href="uploads/files/general.htm" target="_blank"><i class="fas fa-sitemap fa-fw"></i> Organigrama General</a></p>
            <p><a href="uploads/files/operativa.htm" target="_blank"><i class="fas fa-sitemap fa-fw"></i> Anexo Área operativa</a></p>



        </div>

    </div>

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>