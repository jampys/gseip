<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        var jsonSubobjetivos = [];

        $.cargarTablaSubobjetivos=function(){

            $('#subobjetivos-table tbody tr').remove();

            for (var i in jsonSubobjetivos) {

                if (jsonSubobjetivos[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }

                $('#subobjetivos-table tbody').append('<tr indice='+jsonSubobjetivos[i].indice+'>' +
                '<td>'+jsonSubobjetivos[i].nombre+'</td>' +
                    //'<td>'+jsonSubobjetivos[i].empleado+' '+jsonSubobjetivos[i].operacion+'</td>' +
                '<td>'+jsonSubobjetivos[i].area+'</td>' +
                '<td class="text-center"><a class="update-subobjetivo" href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="delete-subobjetivo" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                '</tr>');

            }

        };


        $.ajax({ //ok
            url:"index.php",
            type:"post",
            data:{"action": "objetivos", "operation": "editObjetivoSubobjetivos", "id_objetivo": $('#id_objetivo').val()},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){ //carga el array de subobjetivos
                    //var id = data[indice]['id_area'];
                    jsonSubobjetivos[indice] = data[indice];
                    jsonSubobjetivos[indice].indice = indice;

                });

                $.cargarTablaSubobjetivos();
            }

        });

        $('[data-toggle="tooltip"]').tooltip();


        $('#objetivo-form').validate({
            rules: {
                nombre: {required: true},
                id_proceso: {
                    XOR_with: [
                        '#id_area',
                        'Seleccione un proceso o un área'
                    ]
                },
                id_area: {
                    XOR_with: [
                        '#id_proceso',
                        'Seleccione un proceso o un área'
                    ]

                },
                meta: {required: true},
                actividades: {required: true},
                indicador: {required: true},
                responsable_ejecucion: {required: true},
                responsable_seguimiento: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                meta: "Ingrese la meta",
                actividades: "Ingrese las actividades",
                indicador: "Ingrese el indicador",
                responsable_ejecucion: "Seleccione un responsable ejecución",
                responsable_seguimiento: "Seleccione un responsable seguimiento"
            }

        });



        $('#responsable_ejecucion').closest('.form-group').find(':input').on('keyup', function(e){ //ok
            //alert(e.keyCode);
            var code = (e.keyCode || e.which);
            if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key
                return;
            }

            var items="";

            $.ajax({
                url: "index.php",
                type: "post",
                dataType: "json",
                data: { "term": $(this).val(),  "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                success: function(data) {
                    $.each(data.slice(0, 5),function(index,item)
                    {
                        //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                        items+="<option value='"+item['id_empleado']+"'>"+item['apellido']+' '+item['nombre']+"</option>";
                    });

                    $("#responsable_ejecucion").html(items);
                    $('.selectpicker').selectpicker('refresh');
                }

            });

        });


        $('#responsable_seguimiento').closest('.form-group').find(':input').on('keyup', function(e){ //ok
            //alert(e.keyCode);
            var code = (e.keyCode || e.which);
            if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key
                return;
            }

            var items="";

            $.ajax({
                url: "index.php",
                type: "post",
                dataType: "json",
                data: { "term": $(this).val(),  "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                success: function(data) {
                    $.each(data.slice(0, 5),function(index,item)
                    {
                        //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                        items+="<option value='"+item['id_empleado']+"'>"+item['apellido']+' '+item['nombre']+"</option>";
                    });

                    $("#responsable_seguimiento").html(items);
                    $('.selectpicker').selectpicker('refresh');
                }

            });

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



        //guardar objetivo
        $('#objetivo').on('click', '#submit',function(){
            //alert('guardar contrato');
            if ($("#objetivo-form").valid()){
                var params={};
                params.action = 'objetivos';
                params.operation = 'saveObjetivo';
                params.id_objetivo=$('#id_objetivo').val();
                params.periodo=$('#objetivo #periodo').val();
                params.nombre=$('#nombre').val();
                params.id_proceso=$('#id_proceso').val();
                params.id_area=$('#id_area').val();
                params.id_contrato=$('#id_contrato').val();
                params.meta=$('#meta').val();
                params.actividades=$('#actividades').val();
                params.indicador=$('#indicador').val();
                params.frecuencia=$('#frecuencia').val();
                params.id_responsable_ejecucion=$('#responsable_ejecucion').val();
                params.id_responsable_seguimiento=$('#responsable_seguimiento').val();

                //alert(params.id_compania);

                var jsonSubobjetivosIx = [];
                for ( var item in jsonSubobjetivos ){
                    jsonSubobjetivosIx.push( jsonSubobjetivos[ item ] );
                }
                params.vSubobjetivos = JSON.stringify(jsonSubobjetivosIx);


                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    if(data >=0){
                        $("#myElem").html('Objetivo guardado con exito').addClass('alert alert-success').show();

                    }else{
                        $("#myElem").html('Error al guardar el objetivo').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() {
                        $("#myElem").hide();
                        //$('#content').load('index.php',{action:"objetivos", operation:"refreshGrid"});
                        params={};
                        params.action = "objetivos";
                        params.operation = "refreshGrid";
                        $('#content').load('index.php', params, function(){
                            $('#search_panel').show();
                        });
                                            }, 2000);

                });

            }
            return false;
        });



        $('#objetivo').on('click', '#cancel',function(){ //ok
            params={};
            params.action = "objetivos";
            params.operation = "refreshGrid";
            $('#content').load('index.php', params, function(){
                $('#search_panel').show();
            });

        });


        //Abre modal para agregar nuevo subobjetivo al objetivo
        $('#objetivo').on('click', '#add-subobjetivo', function(e){ //ok
            //alert('popup para agregar subobjetivo');
            params={};
            params.action = "objetivos";
            params.operation="loadSubObjetivo";
            $('#popupbox').load('index.php', params,function(){
                $('#myModal').modal();
                $('#myModal #id').val(jsonSubobjetivos.length); //calcula el prox elemento del array
            });
            return false;
        });


        //Abre modal para actualizar datos del subobjetivo del objetivo
        $('#objetivo').on('click', '.update-subobjetivo', function(e){ //ok
            //alert('actualizar empleado');
            var id = $(this).closest('tr').attr('indice');
            //alert(id);
            params={};
            params.action = "objetivos";
            params.operation="loadSubObjetivo";
            $('#popupbox').load('index.php', params,function(){
                $('#myModal').modal();
                $('#myModal #id').val(jsonSubobjetivos[id].indice);
                $('#myModal #nombre').val(jsonSubobjetivos[id].nombre);
                $('#myModal #id_area').val(jsonSubobjetivos[id].id_area);

            });
            return false;
        });




        //Guarda los cambios luego de insertar o actualizar un subobjetivo del objetivo
        $(document).on('click', '#myModal #submit',function(){ //ok
            //alert('guardar el subobjetivo');

            if ($("#subobjetivo-form").valid()){

                var id = $('#myModal #id').val();
                //alert('el id es '+id);

                if(jsonSubobjetivos[id]) { //si ya existe en el array, lo actualiza
                    //alert('el elemento existe');
                    jsonSubobjetivos[id].id_area = $('#myModal #id_area').val();
                    jsonSubobjetivos[id].area = $('#myModal #id_area option:selected').text();
                    jsonSubobjetivos[id].nombre = $('#myModal #nombre').val();

                    if(!jsonSubobjetivos[id].id_objetivo_sub){ //si no esta en la BD
                        jsonSubobjetivos[id].operacion = 'insert';
                    }else{ //si esta en la BD, lo marca para eliminar
                        jsonSubobjetivos[id].operacion = 'update';
                    }

                }
                else { // si no existe en el array, lo inserta
                    item = {};
                    item.id_area = $('#myModal #id_area').val();
                    item.area = $('#myModal #id_area option:selected').text();
                    item.nombre = $('#myModal #nombre').val();
                    item.operacion = 'insert';
                    jsonSubobjetivos[id] = item;
                    //alert('agregado con exito');
                }

                $.cargarTablaSubobjetivos();
                $('#myModal').modal('hide');

            }
            return false;
        });


        //Elimina un subobjetivo del objetivo
        $('#objetivo').on('click', '.delete-subobjetivo', function(e){ //ok
            //alert('actualizar empleado');
            var id = $(this).closest('tr').attr('indice');
            //alert(jsonSubobjetivos[id].id_empleado_contrato);

            if(!jsonSubobjetivos[id].id_objetivo_sub){ //si no esta en la BD
                //alert('ahhhhh');
                delete jsonSubobjetivos[id]; //lo elimina del array
            }else{ //si esta en la BD, lo marca para eliminar
                jsonSubobjetivos[id].operacion = 'delete';
            }


            $.cargarTablaSubobjetivos();
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

            <select class="form-control selectpicker show-tick" id="periodo" name="periodo">
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

            <select class="form-control selectpicker show-tick" id="id_proceso" name="id_proceso" data-live-search="true" data-size="5">
                <option value="">Seleccione un proceso</option>
                <?php foreach ($view->procesos as $pro){
                    ?>
                    <option value="<?php echo $pro['id_proceso']; ?>"
                        <?php echo ($pro['id_proceso'] == $view->objetivo->getIdProceso() )? 'selected' :'' ?>
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

            <select class="form-control selectpicker show-tick" id="id_area" name="id_area">
                <option value="">Seleccione un área</option>
                <?php foreach ($view->areas as $ar){
                    ?>
                    <option value="<?php echo $ar['id_area']; ?>"
                        <?php echo ($ar['id_area'] == $view->objetivo->getIdArea() )? 'selected' :'' ?>
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

            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato">
                <option value="">Seleccione un contrato</option>
                <?php foreach ($view->contratos as $con){
                    ?>
                    <option value="<?php echo $con['id_contrato']; ?>"
                        <?php echo ($con['id_contrato'] == $view->objetivo->getIdContrato() )? 'selected' :'' ?>
                        >
                        <?php echo $con['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>

        </div>
    </div>


    <div class="form-group required">
        <label class="col-md-4 control-label" for="descripcion">Meta</label>
            <div class="col-md-8">
                <textarea class="form-control" name="meta" id="meta" placeholder="Meta" rows="2"><?php print $view->objetivo->getMeta(); ?></textarea>
            </div>
    </div>


    <div class="form-group required">
        <label class="col-md-4 control-label" for="actividades">Actividades</label>
        <div class="col-md-8">
            <textarea class="form-control" name="actividades" id="actividades" placeholder="Actividades" rows="3"><?php print $view->objetivo->getActividades(); ?></textarea>
        </div>
    </div>

    <div class="form-group required">
        <label for="indicador" class="col-md-4 control-label">Indicador</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="indicador" id="indicador" placeholder="Indicador" value = "<?php print $view->objetivo->getIndicador() ?>">
        </div>
    </div>


    <div class="form-group required">
        <label for="frecuencia" class="col-md-4 control-label">Frecuencia</label>
        <div class="col-md-8">
            <select class="form-control selectpicker show-tick" id="frecuencia" name="frecuencia">
                <option value="">Seleccione una frecuencia</option>
                <?php foreach ($view->frecuencias['enum'] as $fre){
                    ?>
                    <option value="<?php echo $fre; ?>"
                        <?php echo ($fre == $view->objetivo->getFrecuencia() OR ($fre == $view->frecuencias['default'] AND !$view->objetivo->getIdObjetivo()) )? 'selected' :'' ?>
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
            <!--<input type="text" class="form-control responsable-ejecucion-group" id="responsable_ejecucion" name="responsable_ejecucion" placeholder="Responsable ejecución" value ="<?php //print $view->responsable_ejecucion; ?>">
            <input type="hidden" name="id_responsable_ejecucion" id="id_responsable_ejecucion" class="responsable-ejecucion-group" value = "<?php //print $view->objetivo->getIdResponsableEjecucion() ?>" >-->

            <select id="responsable_ejecucion" name="responsable_ejecucion" class="form-control selectpicker" data-live-search="true" title="<?php echo ($view->objetivo->getIdResponsableEjecucion())? "": "Seleccione un responsable de ejecucion";     ?>">
                <option value = "<?php print $view->objetivo->getIdResponsableEjecucion() ?>">
                    <?php print $view->responsable_ejecucion; ?>
                </option>
            </select>
        </div>
    </div>


    <div class="form-group required">
        <label for="responsable_seguimiento" class="col-md-4 control-label">Responsable seguimiento</label>
        <div class="col-md-8">
            <!--<input type="text" class="form-control responsable-seguimiento-group" id="responsable_seguimiento" name="responsable_seguimiento" placeholder="Responsable seguimiento" value ="<?php //print $view->responsable_seguimiento; ?>">
            <input type="hidden" name="id_responsable_seguimiento" id="id_responsable_seguimiento" class="responsable-seguimiento-group" value = "<?php //print $view->objetivo->getIdResponsableSeguimiento() ?>" >-->
            <select id="responsable_seguimiento" name="responsable_seguimiento" class="form-control selectpicker" data-live-search="true" title="<?php echo ($view->objetivo->getIdResponsableSeguimiento())? "": "Seleccione un responsable de seguimiento";     ?>">
                <option value = "<?php print $view->objetivo->getIdResponsableSeguimiento() ?>">
                    <?php print $view->responsable_seguimiento; ?>
                </option>
            </select>

        </div>
    </div>




    <hr/>


    <div class="clearfix">
        <h4 class="pull-left">Sub-objetivos</h4>
        <button class="btn btn-primary btn-sm pull-right" id="add-subobjetivo">Agregar</button>
    </div>


    <div class="table-responsive" id="subobjetivos-table">
        <table class="table table-condensed dpTable table-hover">
            <thead>
            <tr>
                <th class="col-md-6">Nombre</th>
                <th class="col-md-4">Área</th>
                <th class="col-md-1">Editar</th>
                <th class="col-md-1">Eliminar</th>
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



