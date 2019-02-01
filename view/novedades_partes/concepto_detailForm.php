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


        //cancel de formulario de parte-orden
        $('#concepto-form #cancel').on('click', function(){
            //alert('cancelar form parte-orden');
            $('#concepto-form').hide();
        });



        $('#concepto-form').on('change', '#id_empleado', function(e){

            params={};
            params.action = "parte-empleado-concepto";
            params.operation = "getConceptos";
            params.id_convenio = $('#id_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();

            $('#id_concepto').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            //alert(data1[indice]['Task_Name']);
                            var label = data[indice]["concepto"]+' ('+data[indice]["codigo"]+') '+data[indice]["convenio"];
                            $("#id_concepto").append('<option value="'+data[indice]["id_concepto_convenio_contrato"]+'">'+label+'</option>');
                            //alert('ahhhh');

                        });

                        $('.selectpicker').selectpicker('refresh');



                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });




        });



        //Guardar parte-orden luego de ingresar nuevo o editar
        //$('#right_side').on('click', '#submit',function(){ //ok
        $('#orden-form').on('click', '#submit',function(){ //ok
            //alert('guardar orden');

            if ($("#orden-form").valid()){

                var params={};
                params.action = 'parte-orden';
                params.operation = 'saveOrden';
                params.id_parte = $('#id_parte').val();
                params.id_parte_orden = $('#id_parte_orden').val();
                params.nro_parte_diario = $('#nro_parte_diario').val();
                params.orden_tipo = $('#orden_tipo').val();
                params.orden_nro = $('#orden_nro').val();
                params.duracion = $('#duracion').val();
                params.servicio = $('#servicio').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#orden-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Orden guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#orden-form').hide();
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la órden').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#orden-form').validate({
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



<form name ="concepto-form" id="concepto-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->orden->getIdParte() ?>">-->
        <input type="hidden" name="id_parte_empleado_concepto" id="id_parte_empleado_concepto" value="<?php print $view->concepto->getIdParteEmpleadoConcepto() ?>">


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Empleado</label>
            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option
                        value="<?php echo $em['id_parte_empleado']; ?>"
                        id_convenio="<?php echo $em['id_convenio']; ?>"
                        <?php echo ($em['id_parte_empleado'] == $view->concepto->getIdParteEmpleado())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Concepto</label>
            <select class="form-control selectpicker show-tick" id="id_concepto" name="id_concepto" title="Seleccione un concepto" data-live-search="true" data-size="5">
                <!-- se completa dinamicamente desde javascript  -->
            </select>
        </div>


        <div class="form-group required">
            <label class="control-label" for="cantidad">Cantidad</label>
            <input class="form-control" type="text" name="cantidad" id="cantidad" value = "<?php //print $view->orden->getDuracion() ?>" placeholder="Duración">
        </div>


        <div class="form-group">
            <label class="control-label" for="servicio">Motivo</label>
            <textarea class="form-control" name="motivo" id="motivo" placeholder="Motivo" rows="2"><?php //print $view->orden->getServicio(); ?></textarea>
        </div>





    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













