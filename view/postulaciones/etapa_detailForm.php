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
    <input type="hidden" name="id_etapa" id="id_etapa" value="<?php //print $view->puesto->getIdPuesto() ?>">

    <div class="form-group required">
        <label class="control-label" for="codigo">Código</label>
        <input class="form-control" type="text" name="codigo" id="codigo" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Código">
    </div>

    <div class="form-group required">
        <label class="control-label" for="nombre">Nombre</label>
        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php //print $view->puesto->getNombre() ?>" placeholder="Nombre">
    </div>

    <div class="form-group">
        <label class="control-label" for="descripcion">Descripción</label>
        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="2"><?php //print $view->puesto->getDescripcion(); ?></textarea>
    </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
    </div>


</form>













