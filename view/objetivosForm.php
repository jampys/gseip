<script type="text/javascript">


    $(document).ready(function(){

        var jsonSubobjetivos = [];

        $.cargarTablaSubobjetivos=function(){

            $('#empleados-table tbody tr').remove();

            for (var i in jsonEmpleados) {

                if (jsonEmpleados[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }

                $('#empleados-table tbody').append('<tr id_empleado='+jsonEmpleados[i].id_empleado+'>' +
                '<td>'+jsonEmpleados[i].empleado+'</td>' +
                    //'<td>'+jsonEmpleados[i].empleado+' '+jsonEmpleados[i].operacion+'</td>' +
                '<td>'+jsonEmpleados[i].puesto+'</td>' +
                '<td class="text-center"><a class="update-empleado" href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="delete-empleado" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                '</tr>');

            }

        };


        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "contratos", "operation": "editContratoEmpleado", "id_contrato": $('#id_contrato').val()},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){ //carga el array de empleados
                    var id = data[indice]['id_empleado'];
                    jsonEmpleados[id] = data[indice];
                    //alert(jsonEmpleados[id].fecha_desde);

                });

                $.cargarTablaEmpleados();
            }

        });

        $('[data-toggle="tooltip"]').tooltip();


        $('#objetivo-form').validate({
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
                                id_proceso: item.id_proceso,
                                id_area: item.id_area,
                                id_contrato: item.id_contrato,
                                meta: item.meta,
                                actividades: item.actividades,
                                indicador: item.indicador,
                                frecuencia: item.frecuencia

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

                $('#id_proceso').val(ui.item.id_proceso);
                $('#id_area').val(ui.item.id_area);
                $('#id_contrato').val(ui.item.id_contrato);
                $('#meta').val(ui.item.meta);
                $('#actividades').val(ui.item.actividades);
                $('#indicador').val(ui.item.indicador);
                $('#frecuencia').val(ui.item.frecuencia);

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



        //guardar contrato
        $('#contrato').on('click', '#submit',function(){
            //alert('guardar contrato');
            if ($("#contrato-form").valid()){
                var params={};
                params.action = 'contratos';
                params.operation = 'saveContrato';
                params.id_contrato=$('#id_contrato').val();
                params.nro_contrato=$('#nro_contrato').val();
                params.fecha_desde=$('#fecha_desde').val();
                params.fecha_hasta=$('#fecha_hasta').val();
                params.id_responsable=$('#id_responsable').val();
                params.id_compania=$('#compania').val();
                //alert(params.id_compania);

                var jsonEmpleadosIx = [];
                for ( var item in jsonEmpleados ){
                    jsonEmpleadosIx.push( jsonEmpleados[ item ] );
                }
                params.vEmpleados = JSON.stringify(jsonEmpleadosIx);


                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    if(data >=0){
                        $("#myElem").html('Contrato guardado con exito').addClass('alert alert-success').show();

                    }else{
                        $("#myElem").html('Error al guardar el contrato').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                        $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
                    }, 2000);

                });

            }
            return false;
        });



        $('#contrato').on('click', '#cancel',function(){
            //$('#popupbox').dialog('close');
            $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
        });


        //Abre modal para agregar nuevo subobjetivo al objetivo
        $('#objetivo').on('click', '#add-subobjetivo', function(e){
            //alert('popup para agregar subobjetivo');
            params={};
            params.action = "objetivos";
            params.operation="loadSubObjetivo";
            $('#popupbox').load('index.php', params,function(){
                $('#myModal').modal();
            });
            return false;
        });




        //Guarda los cambios luego de insertar o actualizar un subobjetivo del objetivo
        $(document).on('click', '#myModal #submit',function(){
            alert('guardar el subobjetivo');

            if ($("#subobjetivo-form").valid()){

                var id = $('#id_empleado').val();

                if(jsonEmpleados[id]) { //si ya existe en el array, lo actualiza
                    //alert('el elemento existe');
                    jsonEmpleados[id].id_puesto = $("#puesto").val();
                    jsonEmpleados[id].puesto = $("#puesto option:selected").text();
                    jsonEmpleados[id].fecha_desde = $('#myModal #fecha_desde').val();
                    jsonEmpleados[id].fecha_hasta = $('#myModal #fecha_hasta').val();

                    if(!jsonEmpleados[id].id_empleado_contrato){ //si no esta en la BD
                        jsonEmpleados[id].operacion = 'insert';
                    }else{ //si esta en la BD, lo marca para eliminar
                        jsonEmpleados[id].operacion = 'update';
                    }

                }
                else { // si no existe en el array, lo inserta
                    item = {};
                    item.id_empleado = id;
                    item.empleado = $('#empleado').val();
                    item.puesto = $("#puesto option:selected").text();
                    item.id_puesto = $("#puesto").val();
                    item.fecha_desde = $('#myModal #fecha_desde').val();
                    item.fecha_hasta = $('#myModal #fecha_hasta').val();
                    item.operacion = 'insert';
                    jsonEmpleados[id] = item;
                    //alert('agregado con exito');
                }

                $.cargarTablaEmpleados();

            }
            return false;
        });

        //Elimina un empleado del contrato
        $('#contrato').on('click', '.delete-empleado', function(e){
            //alert('actualizar empleado');
            var id = $(this).closest('tr').attr('id_empleado');
            //alert(jsonEmpleados[id].id_empleado_contrato);

            if(!jsonEmpleados[id].id_empleado_contrato){ //si no esta en la BD
                //alert('ahhhhh');
                delete jsonEmpleados[id]; //lo elimina del array
            }else{ //si esta en la BD, lo marca para eliminar
                jsonEmpleados[id].operacion = 'delete';
            }


            $.cargarTablaEmpleados();
            return false;
        });






    });

</script>





<div class="col-md-2"></div>

<div class="col-md-8">


<div class="panel panel-default" id="objetivo">
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
            <textarea class="form-control" name="actividades" id="actividades" placeholder="Actividades" rows="3"><?php //print $view->puesto->getDescripcion(); ?></textarea>
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




    <hr/>


    <div class="clearfix">
        <h4 class="pull-left">Sub-objetivos</h4>
        <button class="btn btn-primary btn-sm pull-right" id="add-subobjetivo">Agregar</button>
    </div>


    <div class="table-responsive" id="empleados-table">
        <table class="table table-condensed dataTable table-hover">
            <thead>
            <tr>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody>
            <!-- se genera dinamicamente desde javascript -->
            </tbody>
        </table>
    </div>



    <hr/>







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



