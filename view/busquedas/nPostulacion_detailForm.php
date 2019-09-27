<style>

    #culo:after {  /* icono de un nodo cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f054";
        /*color: #5fba7d;*/
    }

    #culo.highlight:after {  /* icono de un nodo abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f078";
        /*color: #5fba7d;*/
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){

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
        $('#postulacion-form #cancel').on('click', function(){
            $('#postulacion-form').hide();
            return false;
        });


        $('#contrato-vehiculo-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                id_vehiculo: {
                 required: true,
                 remote: {
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: {
                            action: "contrato-vehiculo",
                            operation: "checkVehiculo",
                            id_vehiculo: function(){ return $('#id_vehiculo').val();},
                            id_contrato: function(){ return $('#myModal #id_contrato').val();},
                            id_contrato_vehiculo: function(){ return $('#id_contrato_vehiculo').val();}
                          }
                 }
                 },
                fecha_desde: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                id_vehiculo: {
                 required: "Seleccione un vehículo",
                 remote: "El vehículo ya se encuentra en el contrato"
                 },
                //id_vehiculo: "Seleccione un vehículo",
                fecha_desde: "Seleccione una fecha desde"
            }

        });


        /*$('#postulacion-form').on('click', '#culo', function(){
            alert('Agregar nuevo postulante');
            //var id = $(this).closest('tr').attr('data-id');
            //preparo los parametros
            params={};
            //params.id_busqueda = id;
            params.action = "busqueda-postulante";
            params.operation = "newPostulante";
            $('#box1').load('index.php', params,function(){
                //$('#myModal').modal();
                //$('#etapas_left_side #add').attr('id_busqueda', id);
                $('.panel').show();
            })

        });*/



        $('#postulacion-form').on('click', '#culo', function() {
            var selected = $(this).hasClass("highlight");
            if(!selected){
                alert('abrir');
                $(this).addClass("highlight");
                params={};
                params.action = "busqueda-postulante";
                params.operation = "newPostulante";
                $('#box1').load('index.php', params,function(){
                    //$('#myModal').modal();
                    //$('#etapas_left_side #add').attr('id_busqueda', id);
                    $('.panel').show();
                })
            }else{
                alert('cerrar');
                $(this).removeClass("highlight");
                $('.panel').hide();
            }

        });




    });

</script>



<form name ="postulacion-form" id="postulacion-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php print $view->postulacion->getIdPostulacion() ?>">
    <input type="hidden" name="id_busqueda" id="id_busqueda" value="<?php print $view->postulacion->getIdBusqueda() ?>">




            <div class="form-group">
                <label for="id_postulante" class="control-label">Postulante</label>
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
                <a href="#" id="culo" title="nuevo postulante">Nuevo postulante&nbsp;</a>

            </div>




        <div class="panel panel-default" style="display: none">
            <div class="panel-body" id="box1" style="background-color: #e5e5e5"></div>
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
            <div class="form-group col-md-6 required">
                <label class="control-label" for="expectativas">Expectativas ($)</label>
                <input class="form-control" type="text" name="expectativas" id="expectativas" value = "<?php print $view->postulacion->getExpectativas() ?>" placeholder="Expectativas">
            </div>
            <div class="form-group col-md-6 required">
                <label class="control-label" for="expectativas">Propuesta económica ($)</label>
                <input class="form-control" type="text" name="propuesta_economica" id="propuesta_economica" value = "<?php print $view->postulacion->getPropuestaEconomica() ?>" placeholder="Propuesta económica">
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













