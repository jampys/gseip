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


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        //cancel de formulario de tareas
        $('#tarea-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-orden');
            $('#tarea-form').hide();
        });



        //Guardar tarea luego de ingresar nueva o editar
        $('#tarea-form').on('click', '#submit',function(){ //ok
            //alert('guardar orden');

            if ($("#tarea-form").valid()){

                var params={};
                params.action = 'obj_tareas';
                params.operation = 'saveTarea';
                params.id_objetivo = $('#id_objetivo').val();
                params.id_tarea = $('#id_tarea').val();
                params.nombre = $('#nombre').val();
                params.descripcion = $('#descripcion').val();
                params.fecha_inicio = $('#fecha_inicio').val();
                params.fecha_fin = $('#fecha_fin').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#tarea-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Tarea guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-tareas').load('index.php',{action:"obj_tareas", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                        //$('#myModal').modal('hide');
                                                        $('#tarea-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la tarea').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
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
            <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="2"><?php print $view->tarea->getDescripcion(); ?></textarea>
        </div>


        <div class="form-group required">
            <label class="control-label" for="empleado">Fecha inicio / fin</label>
            <div class="input-group input-daterange">
                <input class="form-control" type="text" name="fecha_inicio" id="fecha_inicio" value = "<?php print $view->tarea->getFechaInicio() ?>" placeholder="DD/MM/AAAA">
                <div class="input-group-addon">a</div>
                <input class="form-control" type="text" name="fecha_fin" id="fecha_fin" value = "<?php print $view->tarea->getFechaFin() ?>" placeholder="DD/MM/AAAA">
            </div>
        </div>


    <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













