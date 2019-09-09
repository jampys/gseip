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


            $(document).on('click', '.edit', function(){ //ok
                var id=$(this).attr('data-id');
                params={};
                params.id=id;
                params.action = "contratos";
                params.operation = "editContrato";
                $('#content').load('index.php', params,function(){
                    //$('#contrato-form').data('operation', 'edit');
                })
            });


            $(document).on('click', '.view', function(){ //ok
                var id=$(this).attr('data-id');
                params={};
                params.id=id;
                params.action = "contratos";
                params.operation = "editContrato";
                params.target = 'view';
                $('#content').load('index.php', params,function(){
                    //$("#contrato-form input, #contrato-form .selectpicker").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.panel-footer').css('display', 'none');
                    //$('.panel-heading .pull-left').html('');
                    //$('#contrato-form').data('operation', 'view');
                })
            });


            $('#content').on('click', '.vehiculos', function(){
                alert('presiono sobre vehiculos');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_grupo = id;
                params.action = "contrato-vehiculo";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_contrato', id);
                })

            });




            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "contratos";
                params.operation="newContrato";
                $('#content').load('index.php', params,function(){

                })
            });


            $(document).on('click', '.delete', function(){
                //$('#confirm').dialog('open');
                $("#confirm").data('id', $(this).attr('data-id')).dialog("open");
                return false;
            });


            $.fn.borrar = function(id) {
                //alert(id);
                //preparo los parametros
                params={};
                params.id=id;
                params.action = "clientes";
                params.operation = "deleteClient";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Cliente eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"clientes", operation: "refreshGrid"});
                    }else{
                        $("#myElemento").html('Error al eliminar el cliente').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');}, 2000);

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

    <div id="popupbox1"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>