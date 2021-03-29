<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();



            $(document).on('click', '#search', function(){ //ok
                if ($("#search_form").valid()){
                    params={};
                    params.search_contrato = $("#search_contrato").val();
                    params.action = "cuadrillas";
                    params.operation = "refreshGrid";
                    $('#content').load('index.php', params);

                }
                return false;


            });


            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrillas";
                params.operation = "editCuadrilla";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrillas";
                params.operation = "editCuadrilla";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.empleados', function(){ //ok
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_cuadrilla = id;
                params.action = "cuadrilla-empleado";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#empleados_left_side #add').attr('id_cuadrilla', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                if ($("#search_form").valid()){
                    params={};
                    params.action = "cuadrillas";
                    params.operation="newCuadrilla";
                    $('#popupbox').load('index.php', params,function(){
                        $('#myModal').modal();
                    });

                }
                return false;


            });



            $(document).on('click', '#example .delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                return false;
            });


            $('#search_form').validate({
                rules: {
                    search_contrato: {required: true}
                },
                messages:{
                    search_contrato: "Seleccione un contrato"
                }

            });




        });

    </script>




</head>
<body>


<?php require_once('templates/header.php'); ?>


<div class="container">




    <br/>
    <div class="row">


        <div class="col-md-1"></div>

        <div class="col-md-10">

            <h4>Cuadrillas</h4>
            <hr class="hr-primary"/>

                <form id="search_form" name="search_form">

                    <div class="row">

                        <div class="form-group col-md-4">
                            <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                            <select class="form-control selectpicker show-tick" id="search_contrato" name="search_contrato" data-live-search="true" data-size="5">
                                <option value="">Seleccione el contrato</option>
                                <?php foreach ($view->contratos as $con){
                                    ?>
                                    <option value="<?php echo $con['id_contrato']; ?>" >
                                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-2">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default dp_green" title="nueva cuadrilla" id="new" <?php echo ( PrivilegedUser::dhasAction('CUA_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus fa-lg"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-4">

                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-md-6">

                            <div id="myElem" class="msg" style="display:none">
                                <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                            </div>

                        </div>

                    </div>








                </form>



        </div>


        <div class="col-md-1"></div>

    </div>
    <br/>









    <div id="content" class="row">
        <?php include_once ($view->contentTemplate);  ?>
    </div>

</div>

<div id="popupbox"></div>



<?php require_once('templates/footer.php'); ?>


</body>


</html>