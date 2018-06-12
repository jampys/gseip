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



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
    <input type="hidden" name="id_etapa" id="id_etapa" value="<?php print $view->etapa->getIdEtapa() ?>">

    <div class="form-group required">
        <label class="control-label" for="etapa">Etapa</label>
        <input class="form-control" type="text" name="etapa" id="etapa" value = "<?php print $view->etapa->getEtapa() ?>" placeholder="Etapa">
    </div>

    <div class="form-group required">
        <label class="control-label" for="motivo">Motivo</label>
        <input class="form-control" type="text" name="motivo" id="motivo"value = "<?php print $view->etapa->getMotivo() ?>" placeholder="Motivo">
    </div>

    <div class="form-group">
        <label class="control-label" for="comentarios">Comentarios</label>
        <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php print $view->etapa->getComentarios(); ?></textarea>
    </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
    </div>


</form>













