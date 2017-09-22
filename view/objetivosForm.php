﻿<script type="text/javascript">


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


        $("#responsable_ejecucion").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.apellido+" "+item.nombre,
                                id: item.id_empleado

                            };
                        }));
                    }

                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#id_responsable_ejecucion').val(ui.item? ui.item.id : '');
                $('#responsable_ejecucion').val(ui.item.label);
            },
            search: function(event, ui) { $('#id_responsable_ejecucion').val(''); }
        });


        $("#responsable_seguimiento").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.apellido+" "+item.nombre,
                                id: item.id_empleado

                            };
                        }));
                    }

                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#id_responsable_seguimiento').val(ui.item? ui.item.id : '');
                $('#responsable_seguimiento').val(ui.item.label);
            },
            search: function(event, ui) { $('#id_responsable_seguimiento').val(''); }
        });




        $("#nombre").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"objetivos", "operation":"autocompletarObjetivos"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: (!item.id_objetivo_puesto)? item.nombre : item.nombre+' '+item.periodo,
                                objetivo: item.nombre,
                                id_objetivo: item.id_objetivo,
                                id_objetivo_puesto: item.id_objetivo_puesto,
                                valor: item.valor

                            };
                        }));
                    },
                    error: function(data, textStatus, errorThrown) {
                        console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    }

                });
            },
            minLength: 2,
            change: function(event, ui) {

                //Abre modal para agregar nuevo empleado al contrato
                params={};
                params.action = "objetivo-puesto";
                params.operation="editObjetivoPuesto";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModalUpdate').modal();
                    $('#myModalUpdate #objetivo').val(ui.item.objetivo);
                    $('#myModalUpdate #id_objetivo').val(ui.item.id_objetivo);

                    if(ui.item.id_objetivo_puesto){

                        $('#myModalUpdate #valor').val(ui.item.valor);


                    }



                });

                $('#objetivo-puesto #search_objetivo').val('');
                return false;

            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {

            var mlabel = (item.id_objetivo_puesto)? 'label-success': 'label-info';
            var mMessage = (item.id_objetivo_puesto)? 'clonar': 'nuevo';

            return $("<li></li>")
                .data("item.autocomplete", item)
                .append('<a>' + item.label + ' <span class="label '+mlabel+'">'+mMessage+'</span></a>')
                .appendTo(ul);
        };






    });

</script>





<div class="col-md-2"></div>

<div class="col-md-8">


<div class="panel panel-default ">
<div class="panel-heading"><h4><?php echo $view->label ?></h4></div>

<div class="panel-body">




<form class="form-horizontal" name ="objetivo-form" id="objetivo-form" method="POST" action="index.php">
<input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">


    <div class="form-group required">
        <label for="periodo" class="col-md-4 control-label">Período</label>
        <div class="col-md-8">

            <select class="form-control" id="periodo" name="periodo">
                <?php foreach ($view->periodos as $pe){
                    ?>
                    <option value="<?php echo $pe['periodo']; ?>"
                        <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                        >
                        <?php echo $pe['periodo']; ?>
                    </option>
                <?php  } ?>
            </select>

        </div>
    </div>


    <div class="form-group required">
        <label for="nombre" class="col-md-4 control-label">Nombre</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->objetivo->getNombre() ?>">
        </div>
    </div>


    <div class="form-group">
        <label for="id_proceso" class="col-md-4 control-label">Proceso</label>
        <div class="col-md-8">

            <select class="form-control" id="id_proceso" name="id_proceso">
                    <option value=""></option>
                <?php foreach ($view->procesos as $pro){
                    ?>
                    <option value="<?php echo $pro['id_proceso']; ?>"
                        <?php //echo ($pro['id_proceso'] == $view->objetivo->getIdProceso() )? 'selected' :'' ?>
                        >
                        <?php echo $pro['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>

        </div>
    </div>


    <div class="form-group">
        <label for="id_area" class="col-md-4 control-label">Área</label>
        <div class="col-md-8">

            <select class="form-control" id="id_area" name="id_area">
                <option value=""></option>
                <?php foreach ($view->areas as $ar){
                    ?>
                    <option value="<?php echo $ar['id_area']; ?>"
                        <?php //echo ($pro['id_proceso'] == $view->objetivo->getIdProceso() )? 'selected' :'' ?>
                        >
                        <?php echo $ar['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>

        </div>
    </div>


    <div class="form-group">
        <label for="id_contrato" class="col-md-4 control-label">Contrato</label>
        <div class="col-md-8">

            <select class="form-control" id="id_contrato" name="id_contrato">
                <option value=""></option>
                <?php foreach ($view->contratos as $con){
                    ?>
                    <option value="<?php echo $con['id_contrato']; ?>"
                        <?php //echo ($pro['id_proceso'] == $view->objetivo->getIdProceso() )? 'selected' :'' ?>
                        >
                        <?php echo $con['compania']; ?>
                    </option>
                <?php  } ?>
            </select>

        </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for="descripcion">Meta</label>
            <div class="col-md-8">
                <textarea class="form-control" name="meta" id="meta" placeholder="Meta" rows="2"><?php //print $view->puesto->getDescripcion(); ?></textarea>
            </div>
    </div>


    <div class="form-group">
        <label class="col-md-4 control-label" for="actividades">Actividades</label>
        <div class="col-md-8">
            <textarea class="form-control" name="actividades" id="actividades" placeholder="Actividades" rows="2"><?php //print $view->puesto->getDescripcion(); ?></textarea>
        </div>
    </div>

    <div class="form-group required">
        <label for="indicador" class="col-md-4 control-label">Indicador</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="indicador" id="indicador" placeholder="Indicador" value = "<?php //print $view->objetivo->getNombre() ?>">
        </div>
    </div>


    <div class="form-group required">
        <label for="frecuencia" class="col-md-4 control-label">Frecuencia</label>
        <div class="col-md-8">
            <select class="form-control" id="frecuencia" name="frecuencia">
                <option value="" disabled selected>Seleccione la frecuencia</option>
                <?php foreach ($view->frecuencias['enum'] as $fre){
                    ?>
                    <option value="<?php echo $fre; ?>"
                        <?php //echo ($nac == $view->empleado->getNacionalidad() OR ($nac == $view->nacionalidades['default'] AND !$view->empleado->getIdEmpleado()) )? 'selected' :'' ?>
                        >
                        <?php echo $fre; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>
    </div>



    <div class="form-group required">
        <label for="responsable_ejecucion" class="col-md-4 control-label">Responsable ejecución</label>
        <div class="col-md-8">
            <input type="text" class="form-control responsable-ejecucion-group" id="responsable_ejecucion" name="responsable_ejecucion" placeholder="Responsable ejecución" value ="<?php //print $view->responsable; ?>">
            <input type="hidden" name="id_responsable_ejecucion" id="id_responsable_ejecucion" class="responsable-ejecucion-group" value = "<?php //print $view->contrato->getIdResponsable() ?>" >
        </div>
    </div>


    <div class="form-group required">
        <label for="responsable_seguimiento" class="col-md-4 control-label">Responsable seguimiento</label>
        <div class="col-md-8">
            <input type="text" class="form-control responsable-seguimiento-group" id="responsable_seguimiento" name="responsable_seguimiento" placeholder="Responsable seguimiento" value ="<?php //print $view->responsable; ?>">
            <input type="hidden" name="id_responsable_seguimiento" id="id_responsable_seguimiento" class="responsable-seguimiento-group" value = "<?php //print $view->contrato->getIdResponsable() ?>" >
        </div>
    </div>







<div id="myElem" style="display:none"></div>


</form>


</div>



<div class="panel-footer clearfix">
    <div class="button-group pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>
</div>











</div>





</div>

















<div class="col-md-2"></div>



