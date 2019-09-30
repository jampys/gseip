<style>

    #culo:after, #culo2:after {  /* icono de un nodo cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f054";
        /*color: #5fba7d;*/
    }

    #culo.highlight:after, #culo2.highlight:after {  /* icono de un nodo abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f078";
        /*color: #5fba7d;*/
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){

        var objeto={};

        $('#etapas_right_side').data('nuevo', 0);

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        /*$('.input-daterange').datepicker({
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/


        /*$('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });*/


        //cancel de formulario de postulacion
        $('#myModal #cancel').on('click', function(){
            $('#chalampa').hide();
            return false;
        });


        $('#postulacion-form').validate({
            rules: {
                id_postulante: {required: function(){return $('#etapas_right_side').data('nuevo') == 0;}},
                origen_cv: {required: true}

            },
            messages:{
                id_postulante: "Seleccione el postulante",
                origen_cv: "Seleccione el origen del CV"
            }

        });




        $('#chalampa').on('click', '#culo', function(e) { //ok
            var selected = $(this).hasClass("highlight");
            if(!selected){
                //alert('abrir');
                $(this).addClass("highlight");
                //params={};
                //params.action = "busqueda-postulante";
                //params.operation = "newPostulante";
                //$('#box1 .panel-body').load('index.php', params,function(){
                    //$('#myModal').modal();
                    //$('#etapas_left_side #add').attr('id_busqueda', id);
                    $('#box1').show();
                    $('#id_postulante').val('').selectpicker('refresh');
                    $('#id_postulante_form_group').hide();
                    $('#etapas_right_side').data('nuevo', 1);
                    //alert(nuevo);
                //})
            }else{
                //alert('cerrar');
                $(this).removeClass("highlight");
                $('#box1').hide();
                $('#id_postulante_form_group').show();
                $('#etapas_right_side').data('nuevo', 0);
            }
            return false;

        });



        //Guardar postulacion luego de ingresar nueva o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar postulacion');

            //$('#postulacion-form').validate().resetForm(); //limpiar error input validate
            $('#postulacion-form').find('input').closest('.form-group').removeClass('has-error');
            $('#postulante-form').find('input').closest('.form-group').removeClass('has-error');
            $('#postulacion-form .tooltip').remove(); //limpiar error tooltip validate
            $('#postulante-form .tooltip').remove(); //limpiar error tooltip validate

            if ($("#postulacion-form").valid() && $("#postulante-form").valid() ){

                //alert('paso la validacion');
                //throw new Error();
                var params={};
                params.action = 'busqueda-postulante';
                params.operation = 'savePostulacion';
                params.id_busqueda = $('#id_busqueda').val();
                params.id_postulante = $('#id_postulante').val();
                params.id_postulacion = $('#id_postulacion').val();

                params.origen_cv = $('#origen_cv').val();
                params.expectativas = $('#expectativas').val();
                params.propuesta_economica = $('#propuesta_economica').val();
                //alert(params.id_postulante);

                $.post('index.php',params,function(data, status, xhr){
                    alert(xhr.responseText);

                    if(data >=0){
                        $("#chalampa #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Postulación guardada con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side .grid').load('index.php',{action:"busqueda-postulante", id_busqueda:params.id_busqueda, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#chalampa').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la postulación').addClass('alert alert-danger').show();
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    alert('Entro a fail '+jqXHR.responseText);
                    //$("#myElem").html('Error al guardar el vehículo').addClass('alert alert-danger').show();
                });

            }
            return false;
        });




    });

</script>


<div id="chalampa">

    <a href="#" id="culo" title="nuevo postulante">Nuevo postulante&nbsp;</a>

    <div class="panel panel-default" id="box1" style="display: none">
        <div class="panel-body" style="background-color: #e5e5e5">
            <?php include_once('view/busquedas/nPostulante_detailForm.php');?>
        </div>
    </div>


<form name ="postulacion-form" id="postulacion-form" method="POST" action="index.php">
    <fieldset>

        <!--<div class="alert alert-info">
        <strong><?php //echo $view->label ?></strong>
    </div>-->

    <input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php print $view->postulacion->getIdPostulacion() ?>">
    <input type="hidden" name="id_busqueda" id="id_busqueda" value="<?php print $view->postulacion->getIdBusqueda() ?>">




        <div class="form-group" id="id_postulante_form_group">
            <!--<label for="id_postulante" class="control-label">Postulante</label>-->
            <select class="form-control selectpicker show-tick" id="id_postulante" name="id_postulante" title="Seleccione el postulante" data-live-search="true" data-size="5">
                <?php foreach ($view->postulantes as $po){
                    ?>
                    <option value="<?php echo $po['id_postulante']; ?>"
                        <?php echo ($po['id_postulante'] == $view->postulacion->getIdPostulante())? 'selected' :'' ?>
                        >
                        <?php echo $po['apellido']." ".$po['nombre']." ".$po['dni'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="origen_cv" class="control-label">Origen del CV</label>
            <select class="form-control selectpicker show-tick" id="origen_cv" name="origen_cv" title="Seleccione el origen del CV" data-live-search="true" data-size="5">
                <?php foreach ($view->origenes_cv['enum'] as $cv){
                    ?>
                    <option value="<?php echo $cv; ?>"
                        <?php echo ($cv == $view->postulacion->getOrigenCv() OR ($cv == $view->origenes_cv['default'] AND !$view->postulacion->getIdPostulacion()) )? 'selected' :'' ?>
                        >
                        <?php echo $cv; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label" for="expectativas">Expectativas ($)</label>
                <input class="form-control" type="text" name="expectativas" id="expectativas" value = "<?php print $view->postulacion->getExpectativas() ?>" placeholder="Expectativas">
            </div>
            <div class="form-group col-md-6">
                <label class="control-label" for="expectativas">Propuesta económica ($)</label>
                <input class="form-control" type="text" name="propuesta_economica" id="propuesta_economica" value = "<?php print $view->postulacion->getPropuestaEconomica() ?>" placeholder="Propuesta económica">
            </div>
        </div>

    </fieldset>
</form>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>



</div>












