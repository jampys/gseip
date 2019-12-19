<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#habilidad').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 3
                },
                nombre: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos"
                },
                nombre: "Ingrese el nombre"
            }

        });



    });

</script>





<!-- Modal -->
<fieldset <?php echo ( PrivilegedUser::dhasAction('HAB_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">



                <form name ="orden-form" id="orden-form" method="POST" action="index.php">
                    <!--<fieldset>-->

                    <!--<div class="alert alert-info">
                            <strong><?php //echo $view->label ?></strong>
                        </div>-->

                        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->orden->getIdParte() ?>">-->
                        <input type="hidden" name="id_parte_orden" id="id_parte_orden" value="<?php print $view->orden->getIdParteOrden() ?>">

                        <div class="form-group required">
                            <label class="control-label" for="codigo">Nro. parte diario</label>
                            <input class="form-control" type="text" name="nro_parte_diario" id="nro_parte_diario" value = "<?php print $view->orden->getNroParteDiario() ?>" placeholder="Nro. parte diario">
                        </div>

                        <div class="form-group required">
                            <label for="orden_tipo" class="control-label">Tipo orden</label>
                            <select class="form-control selectpicker show-tick" id="orden_tipo" name="orden_tipo" title="Seleccione el tipo de orden">
                                <?php foreach ($view->orden_tipos['enum'] as $nac){
                                    ?>
                                    <option value="<?php echo $nac; ?>"
                                        <?php echo ($nac == $view->orden->getOrdenTipo() OR ($nac == $view->orden_tipos['default'] AND !$view->orden->getIdParteOrden()) )? 'selected' :'' ?>
                                        >
                                        <?php echo $nac; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                        <div class="form-group required">
                            <label class="control-label" for="orden_nro">Nro. orden</label>
                            <input class="form-control" type="text" name="orden_nro" id="orden_nro" value = "<?php print $view->orden->getOrdenNro() ?>" placeholder="Nro. orden">
                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label" for="hs_normal">Hora inicio</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input type="text" class="form-control input-small" name="hora_inicio" id="hora_inicio" value = "<?php print $view->orden->getHoraInicio() ?>" >
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                  
                                <div class="form-group">
                                    <label class="control-label" for="hs_50">Hora fin</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input type="text" class="form-control input-small" name="hora_fin" id="hora_fin" value = "<?php print $view->orden->getHoraFin() ?>" >
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label" for="servicio">Servicio</label>
                            <textarea class="form-control" name="servicio" id="servicio" placeholder="Servicio" rows="2"><?php print $view->orden->getServicio(); ?></textarea>
                        </div>





                        <div id="myElem" class="msg" style="display:none"></div>



                        <!--<div id="footer-buttons" class="pull-right">
                            <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
                            <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
                        </div>-->


                        <!--</fieldset>-->
                    </form>








                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary " id="submit" name="submit" type="submit">Guardar</button>
                    <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>
    </fieldset>



