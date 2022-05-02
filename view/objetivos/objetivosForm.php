<script type="text/javascript">


    $(document).ready(function(){

        tippy('[data-tippy-content]', {
            theme: 'light-border',
            placement: 'right',
            delay: [500,0]
        });

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#objetivo-form').on('change', '#periodo', function(e){
            //alert('seleccionó un periodo');
            //throw new Error();
            params={};
            params.action = "obj_objetivos";
            params.operation = "getPadre";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.periodo = $('#myModal #periodo option:selected').attr('periodo');

            //$('#id_objetivo_superior').empty();
            $('#id_objetivo_superior').html('<option value="">Seleccione un objetivo</option>');


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //alert(Object.keys(data).length);

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["codigo"]+' '+data[indice]["nombre"];
                            $("#id_objetivo_superior").append('<option value="'+data[indice]["id_objetivo"]+'"'
                            //+' fecha_desde="'+data[indice]["fecha_desde"]+'"'
                            //+' fecha_hasta="'+data[indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');

                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        $('#id_objetivo_superior').selectpicker('refresh');

                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });



        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#objetivo-form").valid()){

                var params={};
                params.action = 'obj_objetivos';
                params.operation = 'saveObjetivo';
                params.id_objetivo=$('#id_objetivo').val();
                params.periodo = $('#myModal #periodo option:selected').attr('periodo');
                params.id_plan_evaluacion =$('#myModal #periodo').val();
                params.nombre=$('#nombre').val();
                params.id_puesto=$('#id_puesto').val();
                params.id_area=$('#id_area').val();
                params.id_contrato=$('#id_contrato').val();
                params.meta=$('#meta').val();
                params.meta_valor=$('#meta_valor').val();
                params.indicador=$('#indicador').val();
                params.frecuencia=$('#frecuencia').val();
                params.id_responsable_ejecucion=$('#id_responsable_ejecucion').val();
                params.id_responsable_seguimiento=$('#id_responsable_seguimiento').val();
                params.id_objetivo_superior=$('#id_objetivo_superior').val();

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Objetivo guardado con exito').addClass('alert alert-success').show();
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                                //$('#example').DataTable().ajax.reload();
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el objetivo').addClass('alert alert-danger').show();
                });

            }
            return false;
        });


        //cancel de formulario de postulacion
        $('#myModal #cancel').on('click', function(){
            $('#myModal').modal('hide');
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#objetivo-form').validate({ //ok
            rules: {
                periodo: {required: true},
                nombre: {required: true},
                id_puesto: {
                    XOR_with: [
                        '#id_area',
                        'Seleccione un puesto o un área'
                    ]
                },
                id_area: {
                    XOR_with: [
                        '#id_puesto',
                        'Seleccione un puesto o un área'
                    ]

                },
                meta: {required: true},
                meta_valor: {
                    required: true,
                    number: true,
                    maxlength: 6
                },
                indicador: {required: true},
                //id_responsable_ejecucion: {required: true},
                id_responsable_seguimiento: {required: true}
            },
            messages:{
                periodo: "Seleccione un período",
                nombre: "Ingrese el nombre",
                meta: "Ingrese la meta",
                meta_valor: {
                    required: "Ingrese un valor para la meta",
                    number: "Solo números. Utilice un punto como separador decimal",
                    maxlength: "Máximo 6 dígitos"
                },
                indicador: "Ingrese el indicador",
                //responsable_ejecucion: "Seleccione un responsable ejecución",
                responsable_seguimiento: "Seleccione un responsable seguimiento"
            }

        });



    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="objetivo-form" id="objetivo-form" method="POST" action="index.php">
                    <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">

                    <div class="form-group required">
                        <label for="id_busqueda" class="control-label">Período</label>
                        <select class="form-control selectpicker show-tick" id="periodo" name="periodo" title="Seleccione el periodo" data-live-search="true" data-size="5">
                            <?php foreach ($view->periodos as $pe){
                                ?>
                                <option value="<?php echo $pe['id_plan_evaluacion']; ?>" periodo="<?php echo $pe['periodo']; ?>" <?php echo ($pe['cerrado'])? 'disabled':''; ?>
                                    <?php //echo (  ($view->objetivo->getPeriodo() == $pe['periodo']) ||  (!$view->objetivo->getPeriodo() && $pe['periodo'] == $view->periodo_actual)    )? 'selected' :'' ?>
                                    <?php echo (  ($view->objetivo->getPeriodo() == $pe['periodo'])    )? 'selected' :'' ?>
                                    >
                                    <?php echo $pe['periodo']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="nombre" class="control-label">Objetivo</label>
                        <textarea class="form-control" name="nombre" id="nombre" placeholder="Descripción del objetivo" rows="2"><?php print $view->objetivo->getNombre(); ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="id_puesto" class="control-label">Puesto</label>
                        <select class="form-control selectpicker show-tick" id="id_puesto" name="id_puesto" data-live-search="true" data-size="5">
                            <option value="">Seleccione un puesto</option>
                            <?php foreach ($view->puestos as $pu){
                                ?>
                                <option value="<?php echo $pu['id_puesto']; ?>"
                                    <?php echo ($pu['id_puesto'] == $view->objetivo->getIdPuesto() )? 'selected' :'' ?>
                                    >
                                    <?php echo $pu['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_area" class="control-label">Área</label>
                        <select class="form-control selectpicker show-tick" id="id_area" name="id_area" data-live-search="true" data-size="5">
                            <option value="">Seleccione un área</option>
                            <?php foreach ($view->areas as $ar){
                                ?>
                                <option value="<?php echo $ar['id_area']; ?>"
                                    <?php echo ($ar['id_area'] == $view->objetivo->getIdArea() )? 'selected' :'' ?>
                                    >
                                    <?php echo $ar['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>"
                                    <?php echo ($con['id_contrato'] == $view->objetivo->getIdContrato() )? 'selected' :'' ?>
                                    >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="indicador" class="control-label">Indicador</label>
                        <!--<select class="form-control selectpicker show-tick" id="indicador" name="indicador" data-live-search="true" data-size="5" title="Seleccione un indicador del objetivo">
                            <?php foreach ($view->indicadores['enum'] as $ind){
                                ?>
                                <option value="<?php echo $ind; ?>"
                                    <?php echo ($ind == $view->objetivo->getIndicador() OR ($ind == $view->indicadores['default'] AND !$view->objetivo->getIdObjetivo()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $ind; ?>
                                </option>
                            <?php  } ?>
                        </select>-->
                        <select class="form-control selectpicker show-tick" id="indicador" name="indicador" data-live-search="true" data-size="5" title="Seleccione un indicador del objetivo">
                            <!--<option value="">Seleccione un área</option>-->
                            <?php foreach ($view->indicadores as $i){
                                ?>
                                <option value="<?php echo $i['id_indicador']; ?>"
                                    <?php echo ($i['id_indicador'] == $view->objetivo->getIndicador() )? 'selected' :'' ?>
                                    <?php echo ($i['disabled'])? 'disabled' :'' ?>
                                    >
                                    <?php echo $i['indicador']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="frecuencia" class="control-label" data-tippy-content="Frecuencia de evaluación del avance del objetivo">Frecuencia</label>
                        <select class="form-control selectpicker show-tick" id="frecuencia" name="frecuencia" data-live-search="true" data-size="5" title="Seleccione una frecuencia">
                            <!--<option value="">Seleccione una frecuencia</option>-->
                            <?php foreach ($view->frecuencias['enum'] as $fre){
                                ?>
                                <option value="<?php echo $fre; ?>"
                                    <?php echo ($fre == $view->objetivo->getFrecuencia() OR ($fre == $view->frecuencias['default'] AND !$view->objetivo->getIdObjetivo()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $fre; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label for="meta" class="control-label" data-tippy-content="Meta a alcanzar durante el período de tiempo indicado por la frecuencia">Meta</label>
                            <textarea class="form-control" name="meta" id="meta" placeholder="Descripción de la meta" rows="3"><?php print $view->objetivo->getMeta(); ?></textarea>
                        </div>
                        <div class="form-group col-md-3 required">
                            <label for="meta_valor" class="control-label">Valor</label>
                            <input type="text" class="form-control" name="meta_valor" id="meta_valor" value = "<?php print $view->objetivo->getMetaValor() ?>" placeholder="Valor">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="id_responsable_ejecucion" class="control-label">Responsable ejecución</label>
                        <select id="id_responsable_ejecucion" name="id_responsable_ejecucion" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un responsable ejecución</option>
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php echo ($em['id_empleado'] == $view->objetivo->getIdResponsableEjecucion())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="id_responsable_seguimiento" class="control-label">Responsable seguimiento</label>
                        <select id="id_responsable_seguimiento" name="id_responsable_seguimiento" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable seguimiento">
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php echo ($em['id_empleado'] == $view->objetivo->getIdResponsableSeguimiento())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_objetivo_superior" class="control-label">Objetivo de nivel superior</label>
                        <select class="form-control selectpicker show-tick" id="id_objetivo_superior" name="id_objetivo_superior" data-live-search="true" data-size="5">
                            <option value="">Seleccione un objetivo</option>
                            <?php foreach ($view->objetivos as $obj){
                                ?>
                                <option value="<?php echo $obj['id_objetivo']; ?>"
                                    <?php echo ($obj['id_objetivo'] == $view->objetivo->getIdObjetivoSuperior())? 'selected' :'' ?>
                                    <?php echo ($obj['id_objetivo'] == $view->objetivo->getIdObjetivo())? 'disabled' :'' ?>
                                    >
                                    <?php echo $obj['codigo'].' '.$obj['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                </form>




                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasAction('OBJ_UPDATE', array(1)) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



