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