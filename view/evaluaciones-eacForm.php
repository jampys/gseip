<script type="text/javascript">


    $(document).ready(function(){


        $('#modalEac').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#eac-form').validate({
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
<div class="modal fade" id="modalEac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form class="form-horizontal" name ="eac-form" id="eac-form" method="POST" action="index.php">
                    <input type="hidden" name="id_evaluacion_competencia" id="id_evaluacion_competencia" value="<?php print $view->evaluacion_competencia->getIdEvaluacionCompetencia() ?>">
                    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->evaluacion_competencia->getIdEmpleado() ?>">


                    <!--<div class="form-group required">
                        <label for="nro_contrato" class="col-md-4 control-label">Nro. Contrato</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php //print $view->contrato->getNroContrato() ?>">
                        </div>
                    </div>-->


                    <?php foreach ($view->competencias as $com){
                        ?>


                        <div class="form-group required">
                            <label for="compania" class="col-md-4 control-label"><?php echo $com['nombre']; ?> </label>
                            <div class="col-md-8">
                                <select class="form-control" id="compania" name="compania">
                                    <option value="" disabled selected>Seleccione el puntaje</option>
                                    <?php foreach ($view->puntajes as $p){ ?>
                                        <option value="<?php echo $p['id_puntaje']; ?>"
                                            <?php echo ($view->evaluacion_competencia->getIdEvaluacionCompetencia() && $com['nro_orden'] == $p['nro_orden'])? 'selected' :'' ?>
                                            >
                                            <?php echo $p['nro_orden'];?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>



                    <?php  } ?>




                </form>


                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



