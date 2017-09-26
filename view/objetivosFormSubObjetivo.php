<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#subobjetivo-form').validate({
            rules: {
                nombre: {required: true},
                id_area: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                id_area: "Seleccione un área"
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


                <form name ="subobjetivo-form" id="subobjetivo-form" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nobre" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="puesto" >Área</label>
                        <select class="form-control" id="id_area" name="id_area">
                            <option value="" disabled selected>Seleccione el área</option>
                            <?php foreach ($view->areas as $ar){
                                ?>
                                <option value="<?php echo $ar['id_area']; ?>"
                                    <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $ar['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                </form>

                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Aceptar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



