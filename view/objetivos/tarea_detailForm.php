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


        moment.locale('es');
        $('#fecha').daterangepicker({
            //singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            parentEl: '#myModal',
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
        });


        //cancel de formulario de tareas
        $('#tarea-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-orden');
            $('#tarea-form').hide();
        });


        $('#tarea-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                nombre: {required: true},
                fecha_inicio: {required: true},
                fecha_fin: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                nombre: "Ingrese el nombre",
                fecha_inicio: "Seleccione la fecha de inicio",
                fecha_fin: "Seleccione la fecha de fin"

            }

        });



    });

</script>



<form name ="tarea-form" id="tarea-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->orden->getIdParte() ?>">-->
        <input type="hidden" name="id_tarea" id="id_tarea" value="<?php print $view->tarea->getIdTarea() ?>">

        <div class="form-group required">
            <label class="control-label" for="nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->tarea->getNombre() ?>" placeholder="Nombre">
        </div>


        <div class="form-group">
            <label class="control-label" for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="3"><?php print $view->tarea->getDescripcion(); ?></textarea>
        </div>


        <div class="form-group required">
            <label class="control-label" for="empleado">Fecha inicio / fin</label>
            <!--<div class="input-group input-daterange">
                <input class="form-control" type="text" name="fecha_inicio" id="fecha_inicio" value = "<?php print $view->tarea->getFechaInicio() ?>" placeholder="DD/MM/AAAA" readonly>
                <div class="input-group-addon">a</div>
                <input class="form-control" type="text" name="fecha_fin" id="fecha_fin" value = "<?php print $view->tarea->getFechaFin() ?>" placeholder="DD/MM/AAAA" readonly>
            </div>-->
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php //print $view->avance->getFecha() ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                <i class="glyphicon glyphicon-calendar"></i>
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="button" <?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













