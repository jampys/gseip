﻿<script type="text/javascript">


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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="habilidad" id="habilidad" method="POST" action="index.php">
                    <input type="hidden" name="id_habilidad" id="id_habilidad" value="<?php print $view->habilidad->getIdHabilidad() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="codigo">Código</label>
                        <input class="form-control" type="text" name="codigo" id="codigo" value = "<?php print $view->habilidad->getCodigo() ?>" placeholder="Código">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php print $view->habilidad->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="tipo" >Tipo</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="" disabled selected>Seleccione el tipo</option>
                            <?php foreach ($view->tipos['enum'] as $tipo){
                                ?>
                                <option value="<?php echo $tipo; ?>"
                                    <?php echo ($tipo == $view->habilidad->getTipo() OR ($tipo == $view->tipos['default'] AND !$view->habilidad->getIdHabilidad()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $tipo; ?>
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


