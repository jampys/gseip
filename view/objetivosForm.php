<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#puesto').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },
                nombre: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                nombre: "Ingrese el nombre"
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
                    <input type="hidden" name="id_puesto" id="id_puesto" value="<?php print $view->objetivo->getIdObjetivo() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php print $view->objetivo->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="tipo">Tipo</label>
                        <input class="form-control" type="text" name="tipo" id="tipo" value = "<?php print $view->objetivo->getTipo() ?>" placeholder="Tipo">
                    </div>



                    <div class="form-group">
                        <label class="control-label" for="superior" >Objetivo superior</label>
                        <select class="form-control" id="superior" name="superior">
                            <option value="" disabled selected>Seleccione el objetivo superior</option>
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



