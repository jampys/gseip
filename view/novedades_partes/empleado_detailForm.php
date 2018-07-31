<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //cancel de formulario de parte-empleado
        $('#empleado-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-empleado');
            $('#empleado-form').hide();
        });



        //Guardar parte-empleado luego de ingresar nuevo o editar
        $('#empleado-form').on('click', '#submit',function(){
            //alert('guardar empleado');

            if ($("#empleado-form").valid()){

                var params={};
                params.action = 'parte-empleado';
                params.operation = 'saveEmpleado';
                params.id_parte = $('#id_parte').val();
                params.id_parte_empleado = $('#id_parte_empleado').val();
                params.id_empleado = $('#id_empleado').val();
                params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#empleado-form').hide();
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#empleado-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                id_empleado: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                id_empleado: "Seleccione un empleado"
            }

        });



    });

</script>



<form name ="empleado-form" id="empleado-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->
        <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php print $view->empleado->getIdParteEmpleado() ?>">


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Empleado</label>
            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option value="<?php echo $em['id_empleado']; ?>"
                        <?php echo ($em['id_empleado'] == $view->empleado->getIdEmpleado())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="conductor" class="control-label">Conductor</label>

            <div class="input-group">

                <?php foreach($view->conductor['enum'] as $val){ ?>
                    <label class="radio-inline">
                        <input type="radio" name="conductor" value="<?php echo $val ?>"
                            <?php echo ($val == $view->empleado->getConductor() OR ($val == $view->conductor['default'] AND !$view->etapa->getIdEtapa()))? 'checked' :'' ?>
                            ><?php echo ($val==1)? 'Si':'No' ?>
                    </label>
                <?php } ?>

            </div>
        </div>



        <hr/>


        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_manejo">Hs. manejo (HM)</label>
                    <input class="form-control" type="text" name="hs_manejo" id="hs_manejo" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. manejo" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_viaje">Hs. viaje (HV)</label>
                    <input class="form-control" type="text" name="hs_viaje" id="hs_viaje" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. viaje" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_base">Hs. base (HB)</label>
                    <input class="form-control" type="text" name="hs_base" id="hs_base" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. base" disabled>
                </div>
            </div>

        </div>


        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="vianda">Vianda (VD)</label>
                    <input class="form-control" type="text" name="vianda" id="vianda" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Vianda" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="vianda_extra">Vianda extra (VE)</label>
                    <input class="form-control" type="text" name="vianda_extra" id="vianda_extra" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Vianda extra" disabled>
                </div>
            </div>

            <div class="col-md-4">

            </div>

        </div>









        <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













