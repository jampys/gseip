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


        $('#puesto').validate({ //ok
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },
                nombre: {required: true},
                id_area: {required: true},
                id_nivel_competencia: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                nombre: "Ingrese el nombre",
                id_area: "Seleccione un área",
                id_nivel_competencia: "Seleccione un nivel de competencia"
            }

        });



    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="puesto" id="puesto" method="POST" action="index.php">
                    <input type="hidden" name="id_puesto" id="id_puesto" value="<?php print $view->puesto->getIdPuesto() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="codigo">Código</label>
                        <input class="form-control" type="text" name="codigo" id="codigo" value = "<?php print $view->puesto->getCodigo() ?>" placeholder="Código">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php print $view->puesto->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="id_area" >Área</label>
                        <select class="form-control selectpicker show-tick" id="id_area" name="id_area" title="Seleccione un área">
                            <?php foreach ($view->areas as $area){
                                ?>
                                <option value="<?php echo $area['id_area']; ?>"
                                    <?php echo ($area['id_area'] == $view->puesto->getIdArea())? 'selected' :'' ?>
                                    >
                                    <?php echo $area['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="id_nivel_competencia" >Nivel de competencia</label>
                        <select class="form-control selectpicker show-tick" id="id_nivel_competencia" name="id_nivel_competencia" title="Seleccione un nivel de competencia">
                            <?php foreach ($view->nivelesCompetencias as $nc){
                                ?>
                                <option value="<?php echo $nc['id_nivel_competencia']; ?>"
                                    <?php echo ($nc['id_nivel_competencia'] == $view->puesto->getIdNivelCompetencia())? 'selected' :'' ?>
                                    >
                                    <?php echo $nc['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="id_puesto_superior" >Puesto superior</label>
                        <select class="form-control selectpicker show-tick" id="id_puesto_superior" name="id_puesto_superior" title="Seleccione un puesto superior" data-live-search="true" data-size="5">
                            <?php foreach ($view->puesto_superior as $sup){
                                ?>
                                <option value="<?php echo $sup['id_puesto']; ?>"
                                    <?php echo ($sup['id_puesto'] == $view->puesto->getIdPuestoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $sup['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="2"><?php print $view->puesto->getDescripcion(); ?></textarea>
                    </div>





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



