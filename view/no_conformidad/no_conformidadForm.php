<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        moment.locale('es');
        $('#fecha_cierre').daterangepicker({
            parentEl: '#myModal #no_conformidad_form',
            drops: 'auto',
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        });



        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#no_conformidad_form").valid()){

                var params={};
                params.action = 'nc_no_conformidad';
                params.operation = 'saveNoConformidad';
                params.id_no_conformidad = $('#id_no_conformidad').val();
                params.nombre = $('#nombre').val();
                params.descripcion = $('#descripcion').val();
                params.tipo = $('#tipo').val();
                params.analisis_causa=$('input[name=analisis_causa]:checked').val();
                params.analisis_causa_desc = $('#analisis_causa_desc').val();
                params.tipo_accion = $('#tipo_accion').val();
                params.accion = $('#accion').val();
                params.id_responsable_seguimiento = $('#id_responsable_seguimiento').val();
                params.fecha_cierre=$('#fecha_cierre').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.id_grupo = $('#id_empleado option:selected').attr('id_grupo');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado (solo para el caso del insert) viene de un SP y siempre devuelve 1 o -1 (no lanza excepcion PHP)
                    //alert(xhr.responseText);

                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('No conformidad guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                                //$("#search").trigger("click");
                                                $('#example').DataTable().ajax.reload();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la no conformidad').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#no_conformidad_form').validate({
            rules: {
                nro_no_conformidad: {required: true},
                nombre: {required: true},
                descripcion: {required: true},
                tipo: { required: true},
                tipo_accion: { required: true},
                id_responsable_seguimiento: { required: true}
            },
            messages:{
                nro_no_conformidad: "Ingrese el Nro. No conformidad",
                nombre: "Ingrese el nombre",
                descripcion: "Ingrese la descripción del hallazgo",
                fecha: "Selecione el tipo de no conformidad",
                tipo_accion: "Selecione el tipo de acción",
                id_responsable_seguimiento: "Selecione el responsable de seguimiento"
            }

        });


        $("#myModal #id_empleado").on('changed.bs.select', function (e) {
            //Al seleccionar un grupo, completa automaticamente el campo vencimiento y lo deshabilita.
            if ($('#id_empleado option:selected').attr('id_grupo') !='') {
                //$('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).prop('disabled', true).selectpicker('refresh');
                $('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).selectpicker('refresh');
            }
            else{
                $('#id_vencimiento').selectpicker('val', '').prop('disabled', false).selectpicker('refresh');
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


                <form name ="no_conformidad_form" id="no_conformidad_form" method="POST" action="index.php">
                    <input type="hidden" name="id_no_conformidad" id="id_no_conformidad" value="<?php print $view->no_conformidad->getIdNoConformidad() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nro_no_conformidad">Nro. NC</label>
                        <input class="form-control" type="text" name="nro_no_conformidad" id="nro_no_conformidad" value = "<?php print $view->no_conformidad->getNroNoConformidad() ?>" placeholder="Nro. No conformidad (requerido para carga masica, en adelante autogenerado)">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="referencia">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->no_conformidad->getNombre() ?>" placeholder="Nombre">
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="descripcion">Descripción del hallazgo</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del hallazgo" rows="3"><?php print $view->no_conformidad->getDescripcion(); ?></textarea>
                    </div>


                    <div class="form-group required">
                        <label for="tipo" class="control-label">Tipo</label>
                            <select class="form-control selectpicker show-tick" id="tipo" name="tipo" title="Seleccione el tipo" data-live-search="true" data-size="5">
                                <?php foreach ($view->tipos['enum'] as $tipos){
                                    ?>
                                    <option value="<?php echo $tipos; ?>"
                                        <?php echo ($tipos == $view->no_conformidad->getTipo() OR ($tipos == $view->tipos['default'] AND !$view->no_conformidad->getIdNoConformidad()) )? 'selected' :'' ?>
                                        >
                                        <?php echo $tipos; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>


                    <div class="form-group">
                        <label for="analisis_causa" class="control-label">Análisis de causa raiz</label><br/>
                            <?php foreach($view->analisis_causa['enum'] as $val){ ?>
                                <label class="radio-inline">
                                    <input type="radio" name="analisis_causa" value="<?php echo $val ?>"
                                        <?php echo ($val == $view->no_conformidad->getAnalisisCausa() OR ($val == $view->analisis_causa['default'] AND !$view->no_conformidad->getIdNoConformidad()))? 'checked' :'' ?>
                                        ><?php echo $val ?>
                                </label>
                            <?php } ?>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="analisis_causa_desc">Causa raiz</label>
                        <textarea class="form-control" name="analisis_causa_desc" id="analisis_causa_desc" placeholder="Descripción de causa raiz" rows="3"><?php print $view->no_conformidad->getAnalisisCausaDesc(); ?></textarea>
                    </div>


                    <div class="form-group required">
                        <label for="tipo_accion" class="control-label">Tipo de acción</label>
                        <select class="form-control selectpicker show-tick" id="tipo_accion" name="tipo_accion" title="Seleccione el tipo de acción" data-live-search="true" data-size="5">
                            <?php foreach ($view->tipo_accion['enum'] as $ta){
                                ?>
                                <option value="<?php echo $ta; ?>"
                                    <?php echo ($ta == $view->no_conformidad->getTipoAccion() OR ($ta == $view->tipo_accion['default'] AND !$view->no_conformidad->getIdNoConformidad()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $ta; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="descripcion">Acción inmediata</label>
                        <textarea class="form-control" name="accion" id="accion" placeholder="Acción inmediata" rows="3"><?php print $view->no_conformidad->getAccionInmediata(); ?></textarea>
                    </div>


                    <div class="form-group required">
                        <label for="id_responsable_seguimiento" class="control-label">Responsable seguimiento</label>
                        <select id="id_responsable_seguimiento" name="id_responsable_seguimiento" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable seguimiento">
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php echo ($em['id_empleado'] == $view->no_conformidad->getIdResponsableSeguimiento())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha cierre</label>
                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="fecha_cierre" id="fecha_cierre" value = "<?php print $view->no_conformidad->getFechaCierre() ?>" placeholder="DD/MM/AAAA" readonly>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                    </div>



                </form>




                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php echo (!PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) || $view->target=='view' )? 'disabled' : '';  ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



