<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#client').validate({
            rules: {
                nombre: {
                    required: true
                }
            },
            messages:{
                nombre: "Ingrese su nombre"
            }
        });


        $('#fecha').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="client" id="client" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php print $view->client->getId() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->client->getNombre() ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="apellido">Apellido</label>
                        <input class="form-control" type="text" name="apellido" id="apellido"value = "<?php print $view->client->getApellido() ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha</label>

                        <!--<input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->client->getFecha() ?>">
                        <p class="help-block"> dd/mm/yyyy </p>-->
                        <div class="input-group date">
                            <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php print $view->client->getFecha() ?>">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="peso">Peso</label>
                        <input class="form-control" type="text" name="peso" id="peso" value = "<?php print $view->client->getPeso() ?>">
                    </div>
                    <!--<div class="form-group">
                        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                        <button class="btn btn-primary btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
                    </div>-->
                </form>

                <div id="myElem" style="display:none">

                </div>



            </div>
            <div class="modal-footer">

                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>-->
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>



