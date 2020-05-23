<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('#popupbox').dialog({
                autoOpen:false
            });


            $('#content').on('click', '.edit', function(){ //ok
                var id=$(this).attr('data-id');
                params={};
                params.id=id;
                params.action = "contratos";
                params.operation = "editContrato";
                $('#content').load('index.php', params,function(){
                    //$('#contrato-form').data('operation', 'edit');
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id=$(this).attr('data-id');
                params={};
                params.id=id;
                params.action = "contratos";
                params.operation = "editContrato";
                params.target = 'view';
                $('#content').load('index.php', params,function(){
                    //$('.panel-footer').css('display', 'none');

                });
            });


            $('#content').on('click', '.vehiculos', function(){ //ok
                //alert('presiono sobre vehiculos');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_contrato = id;
                params.action = "contrato-vehiculo";
                //params.operation = "etapas"; //entra en default
                $('#popupbox1').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_contrato', id);
                })

            });




            $('#content').on('click', '#new', function(){ //ok
                params={};
                params.action = "contratos";
                params.operation="newContrato";
                $('#content').load('index.php', params,function(){

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

    <div id="popupbox1"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>