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


        moment.locale('es');
        $('#fecha_programada').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            parentEl: '#myModal',
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        });


        $('#fecha').daterangepicker({
            parentEl: '#myModal #capacitacion-form',
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            linkedCalendars: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
            picker.element.valid();
        });
        var drp = $('#fecha').data('daterangepicker');



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
                id_responsable_ejecucion: {required: true},
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
                responsable_ejecucion: "Seleccione un responsable ejecución",
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


                <form name ="capacitacion-form" id="capacitacion-form" method="POST" action="index.php">
                    <input type="hidden" name="id_capacitacion" id="id_capacitacion" value="<?php print $view->capacitacion->getIdCapacitacion() ?>">

                    <div class="form-group required">
                        <label for="id_busqueda" class="control-label">Período</label>
                        <select class="form-control selectpicker show-tick" id="periodo" name="periodo" title="Seleccione el periodo" data-live-search="true" data-size="5">
                            <?php foreach ($view->periodos as $pe){
                                ?>
                                <option value="<?php echo $pe['id_plan_capacitacion']; ?>" periodo="<?php echo $pe['periodo']; ?>" <?php echo ($pe['cerrado'])? 'disabled':''; ?>
                                    <?php echo (  ($view->capacitacion->getPeriodo() == $pe['periodo'])    )? 'selected' :'' ?>
                                    >
                                    <?php echo $pe['periodo']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_puesto" class="control-label">Categoría</label>
                        <select class="form-control selectpicker show-tick" id="id_categoria" name="id_categoria" title="Seleccione una categoría" data-live-search="true" data-size="5">
                            <?php foreach ($view->categorias as $cat){
                                ?>
                                <option value="<?php echo $cat['id_categoria']; ?>"
                                    <?php echo ($cat['id_categoria'] == $view->capacitacion->getIdCategoria() )? 'selected' :'' ?>
                                    >
                                    <?php echo $cat['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="tema" class="control-label">Tema</label>
                        <input class="form-control" type="text" name="tema" id="tema" value="<?php print $view->capacitacion->getTema() ?>">
                    </div>


                    <div class="form-group required">
                        <label for="descripcion" class="control-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción de la capacitacion" rows="2"><?php print $view->capacitacion->getDescripcion(); ?></textarea>
                    </div>

                    <div class="form-group required">
                        <label for="capacitador" class="control-label">Capacitador</label>
                        <input class="form-control" type="text" name="capacitador" id="capacitador" value="<?php print $view->capacitacion->getCapacitador() ?>">
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha programada</label>
                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="fecha_programada" id="fecha_programada" value = "<?php print $view->capacitacion->getFechaProgramada() ?>" placeholder="DD/MM/AAAA" readonly>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label for="duracion" class="control-label">Duración (en hs)</label>
                            <input class="form-control" type="text" name="duracion" id="duracion" value="<?php print $view->capacitacion->getDuracion() ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label" for="">Fechas desde / hasta</label>
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php echo $view->capacitacion->getFechaInicio()." - ".$view->capacitacion->getFechaFin();  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
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
                        <select class="form-control selectpicker show-tick" id="indicador" name="indicador" data-live-search="true" data-size="5" title="Seleccione un indicador del objetivo">
                            <!--<option value="">Seleccione un indicador del objetivo</option>-->
                            <?php foreach ($view->indicadores['enum'] as $ind){
                                ?>
                                <option value="<?php echo $ind; ?>"
                                    <?php echo ($ind == $view->objetivo->getIndicador() OR ($ind == $view->indicadores['default'] AND !$view->objetivo->getIdObjetivo()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $ind; ?>
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


                    <div class="form-group required">
                        <label for="id_responsable_ejecucion" class="control-label">Responsable ejecución</label>
                        <select id="id_responsable_ejecucion" name="id_responsable_ejecucion" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable ejecución">
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



