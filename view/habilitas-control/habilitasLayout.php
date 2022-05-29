﻿<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $(document).on('click', '#search', function(){ //ok
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');

                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                /*params={};
                params.search_busqueda = $("#search_busqueda").val();
                params.search_input = $("#search_input").val();
                params.action = "habilitas-control";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params);*/
                $('#example').DataTable().ajax.reload();
            });


            /*$('#content').on('click', '.edit', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    $('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });*/


            /*$('#content').on('click', '.view', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });*/



            /*$(document).on('click', '#new', function(){
                params={};
                params.action = "postulaciones";
                params.operation="newPostulacion";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });*/






        });

    </script>




</head>
<body>


<?php require_once('templates/header.php'); ?>


<div class="container">




    <br/>
    <div class="row">


        <!--<div class="col-md-1"></div>-->

        <div class="col-md-12">

            <h4>Consulta de habilitas</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <!--<label for="search_busqueda" class="control-label">Tipo de búsqueda</label>-->
                        <select class="form-control selectpicker show-tick" id="search_busqueda" name="search_busqueda" data-live-search="true" data-size="5">
                            <!--<option value="">Seleccione la búsqueda</option>-->
                            <option value="ot">OT Orden de trabajo</option>
                            <option value="habilita">Nro. de habilita</option>
                            <option value="certificado">Nro. de certificado</option>

                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <!--<label class="control-label" for="search_input">&nbsp;</label>-->
                        <input class="form-control" type="text" name="search_input" id="search_input" value = "<?php //print $view->cuadrilla->getNombre() ?>" >
                    </div>

                    <div class="form-group col-md-2">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <i class="fas fa-search fa-lg dp_blue"></i>
                        </button>
                    </div>


                    <div class="form-group col-md-4">

                    </div>


                </form>
            </div>


        </div>


        <!--<div class="col-md-1"></div>-->

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