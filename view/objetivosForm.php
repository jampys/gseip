<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#objetivo').validate({
            rules: {
                nombre: {required: true},
                tipo: {required: true},
                superior: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                tipo: "Seleccione el tipo",
                superior: "Seleccione el objetivo de nivel superior"
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


                <form name ="objetivo" id="objetivo" method="POST" action="index.php">
                    <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php print $view->objetivo->getNombre() ?>" placeholder="Nombre">
                    </div>


                    <div class="form-group required">
                        <label for="tipo" class="control-label">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo">
                                <option value="" disabled selected>Seleccione el tipo</option>
                                <?php foreach ($view->tipos['enum'] as $tipo){
                                    ?>
                                    <option value="<?php echo $tipo; ?>"
                                        <?php echo ($tipo == $view->objetivo->getTipo() )? 'selected' :'' ?>
                                        >
                                        <?php echo $tipo; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>



                    <div class="form-group">
                        <label class="control-label" for="superior" >Objetivo superior</label>
                        <select class="form-control" id="superior" name="superior">
                            <option value="">Seleccione el objetivo superior</option>
                            <?php foreach ($view->superior as $sup){
                                ?>
                                <option value="<?php echo $sup['id_objetivo']; ?>"
                                    <?php echo ($sup['id_objetivo'] == $view->objetivo->getObjetivoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $sup['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
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



