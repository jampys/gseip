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
        $('#empleado-form').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#empleado-form").valid()){

                var params={};
                params.action = 'parte-empleado';
                params.operation = 'saveEmpleado';
                params.id_parte = $('#id_parte').val();
                params.id_parte_empleado = $('#id_parte_empleado').val();
                params.id_empleado = $('#id_empleado').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                params.conductor = $('#conductor').prop('checked')? 1:0;
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
                        $('#left_side .grid-conceptos').load('index.php',{action:"parte-empleado-concepto", id_parte: params.id_parte, operation:"refreshGrid"});
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
                id_empleado: {required: true}
                /*conductor: {
                    //required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "parte-empleado",
                            operation: "checkEmpleado",
                            id_parte_empleado: function(){ return $('#id_parte_empleado').val();},
                            id_parte: function(){ return $('#id_parte').val();}
                        }
                    }
                }*/
            },
            messages:{
                id_empleado: "Seleccione un empleado"
                /*conductor: {
                    //required: "Seleccione un empleado",
                    remote: "La cuadrilla tiene asignado otro conductor"
                }*/
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


        <!--<div class="form-group required">
            <label for="conductor" class="control-label">Conductor</label>

            <div class="input-group">

                <?php //foreach($view->conductor['enum'] as $val){ ?>
                    <label class="radio-inline">
                        <input type="radio" name="conductor" value="<?php //echo $val ?>"
                            <?php //echo ($val == $view->empleado->getConductor() OR ($val == $view->conductor['default'] AND !$view->etapa->getIdEtapa()))? 'checked' :'' ?>
                            ><?php //echo ($val==1)? 'Si':'No' ?>
                    </label>
                <?php //} ?>

            </div>
        </div>-->


        <div class="form-group required">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="conductor" name="conductor" <?php echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para la persona que maneja">Conductor</a>
                </label>
            </div>
        </div>



        <!--<hr/>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_manejo">Hs. manejo (HM)</label>
                    <input class="form-control" type="text" name="hs_manejo" id="hs_manejo" value = "<?php //print $view->empleado->getHsManejo() ?>" placeholder="Hs. manejo" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_viaje">Hs. viaje (HV)</label>
                    <input class="form-control" type="text" name="hs_viaje" id="hs_viaje" value = "<?php //print $view->empleado->getHsViaje() ?>" placeholder="Hs. viaje" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="hs_base">Hs. base (HB)</label>
                    <input class="form-control" type="text" name="hs_base" id="hs_base" value = "<?php //print $view->empleado->getHsBase() ?>" placeholder="Hs. base" disabled>
                </div>
            </div>

        </div>


        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="vianda_diaria">Vianda diaria (VD)</label>
                    <input class="form-control" type="text" name="vianda_diaria" id="vianda_diaria" value = "<?php //print $view->empleado->getViandaDiaria() ?>" placeholder="Vianda diaria" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="vianda_extra">Vianda extra (VE)</label>
                    <input class="form-control" type="text" name="vianda_extra" id="vianda_extra" value = "<?php //print $view->empleado->getViandaExtra() ?>" placeholder="Vianda extra" disabled>
                </div>
            </div>

            <div class="col-md-4">

            </div>

        </div>-->









        <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













