﻿<script type="text/javascript">




    $(document).ready(function(){

        var t = $('#culin').DataTable({
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            "columnDefs": [
                {"width": "44%", "targets": 0}, //empleado
                {"width": "34%", "targets": 1}, //puesto
                //{"width": "11%", "targets": 2}, //fecha_desde
                //{"width": "11%", "targets": 3}, //fecha_hasta
                {"width": "10%", "targets": 4} //ver
                //{"width": "5%", "targets": 5}, //editar
                //{"width": "5%", "targets": 6} //eliminar
            ]

        });

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        var jsonEmpleados = [];


        $.cargarTablaEmpleados=function(){

            //$('#empleados-table tbody tr').remove();
            t.clear().draw();

            //alert('<?php //echo $view->target ?>');

            for (var i in jsonEmpleados) {

                if (jsonEmpleados[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }

                /*$('#empleados-table tbody').append('<tr id_empleado='+jsonEmpleados[i].id_empleado+'>' +
                '<td>'+jsonEmpleados[i].empleado+'</td>' +
                    //'<td>'+jsonEmpleados[i].empleado+' '+jsonEmpleados[i].operacion+'</td>' +
                '<td>'+jsonEmpleados[i].puesto+'</td>' +
                '<td class="text-center"><a class="view-empleado" href="#"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="<?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? 'update-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="<?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? 'delete-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                '</tr>');*/

                var fecha_hasta = (jsonEmpleados[i].fecha_hasta === null)? '':jsonEmpleados[i].fecha_hasta;

                var table_rows = '<tr id_empleado='+jsonEmpleados[i].id_empleado+'>' +
                    '<td>'+jsonEmpleados[i].empleado+'</td>' +
                        //'<td>'+jsonEmpleados[i].empleado+' '+jsonEmpleados[i].operacion+'</td>' +
                    '<td>'+jsonEmpleados[i].puesto+'</td>' +
                    '<td>'+jsonEmpleados[i].fecha_desde+'</td>' +
                    '<td>'+fecha_hasta+'</td>' +
                    '<td class="text-center">' +
                    '<a class="view-empleado" href="#"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;'+
                    '<a class="<?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? 'update-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;'+
                    '<a class="<?php echo ( PrivilegedUser::dhasPrivilege('CON_DEL', $view->contrato->getDomain() ) && $view->target!='view' )? 'delete-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>'+
                    '</td>'+
                    '</tr>';

                t.rows.add($(table_rows)).draw();

            }

        };


        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "contratos", "operation": "editContratoEmpleado", "id_contrato": $('#id_contrato').val()},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){ //carga el array de empleados

                    //alert(data[indice]['id_proceso']);
                    var id = data[indice]['id_empleado'];
                    jsonEmpleados[id] = data[indice];

                    var arr = [];
                    $.each(data[indice]['id_proceso'], function(i, v){
                        arr.push(data[indice]['id_proceso'][i]['id_proceso']);
                    });
                    //alert(arr);
                    jsonEmpleados[id]['id_proceso'] = arr;
                    jsonEmpleados[id]['id_proceso_old'] = arr;

                });

                $.cargarTablaEmpleados();
            }

        });



        $('[data-toggle="tooltip"]').tooltip();

        $('#contrato-form').validate({ //ok
            rules: {
                nro_contrato: {
                    required: true,
                    digits: true},
                compania: {required: true},
                nombre: {required: true},
                id_responsable: {required: true},
                fecha_desde: {required: true},
                fecha_hasta: {required: true}
            },
            messages:{
                nro_contrato: {
                    required: "Ingrese nro. de contrato",
                    digits: "Ingrese solo números"
                },
                compania: "Ingrese la compañía",
                nombre: "Ingrese un nombre",
                id_responsable: "Seleccione un responsable",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"
            }

        });



        /*$('#id_responsable').closest('.form-group').find(':input').on('keyup', function(e){ //ok
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

                    $("#id_responsable").html(items);
                    $('.selectpicker').selectpicker('refresh');
                }

            });

        });*/



        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        /*$('#fecha_desde').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_hasta').datepicker('setStartDate', minDate);
                //$('#fecha_hasta').datepicker('setStartDate', minDate).datepicker('update', minDate);
        });

        $('#fecha_hasta').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_desde').datepicker('setEndDate', maxDate);
        });*/



        //guardar contrato
        $('#contrato').on('click', '#submit',function(){ //ok
            //alert('guardar contrato');
            if ($("#contrato-form").valid()){
                var params={};
                params.action = 'contratos';
                params.operation = 'saveContrato';
                params.id_contrato=$('#id_contrato').val();
                params.nro_contrato=$('#nro_contrato').val();
                params.nombre=$('#nombre').val();
                params.fecha_desde=$('#fecha_desde').val();
                params.fecha_hasta=$('#fecha_hasta').val();
                params.id_localidad=$('#id_localidad').val();
                params.id_responsable=$('#id_responsable').val();
                params.id_compania=$('#compania').val();
                //alert(params.id_responsable);

                var jsonEmpleadosIx = [];
                for ( var item in jsonEmpleados ){
                    jsonEmpleadosIx.push( jsonEmpleados[ item ] );
                }
                params.vEmpleados = JSON.stringify(jsonEmpleadosIx);


                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado viene de una transaccion (try catch) que siempre devuelve 1 o -1
                    //alert(xhr.responseText);
                    if(data >=0){
                        $(".panel-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Contrato guardado con exito').addClass('alert alert-success').show();
                        setTimeout(function() { $("#myElem").hide();
                                                $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
                                              }, 2000);

                    }else{
                        $("#myElem").html('Error al guardar el contrato').addClass('alert alert-danger').show();
                    }
                }, 'json');

            }
            return false;
        });



        $('#contrato').on('click', '#cancel',function(){ //ok
            //$('#popupbox').dialog('close');
            $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
        });

        $(document).on('click', '#back',function(){
            $("#cancel").trigger("click");
        });


        //Abre modal para agregar nuevo empleado al contrato
        $('#contrato').on('click', '#add-empleado', function(e){ //ok
            //alert('insertar empleado');
            params={};
            params.action = "contratos";
            params.operation="loadEmpleado";
            params.id_empleado = '';
            $('#popupbox1').load('index.php', params,function(){
                $('#myModal').modal();
                //alert('add empleado');
            });
            return false;
        });

        //Abre modal para actualizar datos del empleado en contrato
        $('#contrato').on('click', '.update-empleado', function(e){ //ok
            //alert('actualizar empleado');
            var id = $(this).closest('tr').attr('id_empleado');
            //alert(jsonEmpleados[id].id_empleado);
            params={};
            params.action = "contratos";
            params.operation="loadEmpleado";
            params.id_empleado = id;
            $('#popupbox1').load('index.php', params,function(){
                $('#myModal').modal();
                /*$('#id_empleado').append($('<option>', {
                    value: jsonEmpleados[id].id_empleado,
                    text : jsonEmpleados[id].empleado
                }));*/
                //$('#id_empleado').selectpicker('val', jsonEmpleados[id].id_empleado).selectpicker('refresh'); //asigna valor y refresh de #id_empleado
                $('#id_empleado').val(jsonEmpleados[id].id_empleado);
                $("#id_empleado").prop('disabled', true);

                $('#puesto').val(jsonEmpleados[id].id_puesto);
                $('#id_proceso').val(jsonEmpleados[id].id_proceso);
                $('#myModal #fecha_desde').datepicker('setDate', jsonEmpleados[id].fecha_desde );
                $('#myModal #fecha_hasta').datepicker('setDate', jsonEmpleados[id].fecha_hasta );
                $('#myModal #id_localidad').val(jsonEmpleados[id].id_localidad);
                $('.selectpicker').selectpicker('refresh'); //refresh de puesto y procesos


            });
            return false;
        });


        //Abre modal para ver datos del empleado en contrato
        $('#contrato').on('click', '.view-empleado', function(e){ //ok
            var id = $(this).closest('tr').attr('id_empleado');
            params={};
            params.action = "contratos";
            params.operation="loadEmpleado";
            params.id_empleado = id;
            $('#popupbox1').load('index.php', params,function(){
                $('#id_empleado').val(jsonEmpleados[id].id_empleado);
                $("#id_empleado").prop('disabled', true);
                $('#puesto').val(jsonEmpleados[id].id_puesto);
                $('#id_proceso').val(jsonEmpleados[id].id_proceso);
                $('#myModal #fecha_desde').datepicker('setDate', jsonEmpleados[id].fecha_desde );
                $('#myModal #fecha_hasta').datepicker('setDate', jsonEmpleados[id].fecha_hasta );
                $('#myModal #id_localidad').val(jsonEmpleados[id].id_localidad);
                $('.selectpicker').selectpicker('refresh'); //refresh de puesto y procesos
                //deshabilito campos
                $("#empleado-form input, #empleado-form .selectpicker, #empleado-form textarea").prop("disabled", true);
                $('.selectpicker').selectpicker('refresh');
                $('.modal-footer').css('display', 'none');
                //$('#myModalLabel').html('');
                $('#myModal').modal();

            });
            return false;
        });




        //Cada vez que carga este documento, elimina el los eventos click de #myModal #submit. No hubo otra manera de eliminar la repeticion de evento...
        $(document).off('click', '#myModal #submit'); //ok

        //Guarda los cambios luego de insertar o actualizar un empleado del contrato
        $(document).on('click', '#myModal #submit',function(){ //ok

            if ($("#empleado-form").valid()){

            var id = $('#id_empleado').val();

            if(jsonEmpleados[id]) { //si ya existe en el array, lo actualiza
                //alert('el elemento existe');
                jsonEmpleados[id].id_puesto = $("#puesto").val();
                //jsonEmpleados[id].id_proceso = $("#id_proceso").val();
                jsonEmpleados[id].puesto = $("#puesto option:selected").text();
                jsonEmpleados[id].fecha_desde = $('#myModal #fecha_desde').val();
                jsonEmpleados[id].fecha_hasta = $('#myModal #fecha_hasta').val();
                jsonEmpleados[id].id_localidad = $('#myModal #id_localidad').val();

                if(!jsonEmpleados[id].id_empleado_contrato){ //si no esta en la BD
                    jsonEmpleados[id].operacion = 'insert';
                    jsonEmpleados[id].id_proceso = $("#id_proceso").val();
                }else{ //si esta en la BD, es un update
                    jsonEmpleados[id].operacion = 'update';

                    var aDelete = $(jsonEmpleados[id]['id_proceso_old']).not($("#id_proceso").val()).get();
                    $.each(aDelete, function(index, value) {
                        aDelete[index] = value * -1;
                    });
                    //aMerge: tiene en negativo los que hay que eliminar (delete), y en positivo los que se mantienen o hay que agregar (insert)
                    //var aMerge = $.merge( $("#id_proceso").val(), aDelete );
                    var aMerge = $.extend({}, $("#id_proceso").val(), aDelete); // $.merge falla cuando $("#id_proceso").val() no tiene valores
                    jsonEmpleados[id].id_proceso = aMerge;

                }

            }
            else { // si no existe en el array, lo inserta
                item = {};
                item.id_empleado = id;
                //item.empleado = $('#empleado').val();
                item.empleado = $("#id_empleado option:selected").text();
                item.puesto = $("#puesto option:selected").text();
                item.id_puesto = $("#puesto").val();
                item.id_proceso = $("#id_proceso").val();
                item.fecha_desde = $('#myModal #fecha_desde').val();
                item.fecha_hasta = $('#myModal #fecha_hasta').val();
                item.id_localidad = $('#myModal #id_localidad').val();
                item.operacion = 'insert';
                jsonEmpleados[id] = item;
                //alert('agregado con exito');
            }

                //alert(jsonEmpleados[id].id_proceso);


            $.cargarTablaEmpleados();
            $('#myModal').modal('hide');

            }
            return false;
        });

        //Elimina un empleado del contrato
        $('#contrato').on('click', '.delete-empleado', function(e){ //ok
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


<div class="panel panel-default" id="contrato">

    <div class="panel-heading">
        <h4 class="pull-left"><span><?php echo $view->label ?></span></h4>

        <a id="back" class="pull-right" href="#"><i class="fas fa-arrow-left fa-fw"></i>&nbsp;Volver </a>
        <div class="clearfix"></div>

    </div>


    <div class="panel-body">

    <form class="form-horizontal" name ="contrato-form" id="contrato-form" method="POST" action="index.php">

        <fieldset <?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? '' : 'disabled' ?>>

            <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">

            <div class="form-group required">
                <label for="nro_contrato" class="col-md-3 control-label">Nro. Contrato</label>
                <div class="col-md-7">
                    <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php print $view->contrato->getNroContrato() ?>">
                </div>
            </div>

            <div class="form-group required">
                <label for="compania" class="col-md-3 control-label">Compañía</label>
                <div class="col-md-7">
                    <select class="form-control selectpicker show-tick" id="compania" name="compania" title="Seleccione la compañía">
                        <?php foreach ($view->companias as $cia){
                            ?>
                            <option value="<?php echo $cia['id_compania']; ?>"
                                <?php echo ($cia['id_compania'] == $view->contrato->getIdCompania())? 'selected' :'' ?>
                                >
                                <?php echo $cia['nombre'];?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
            </div>


            <div class="form-group required">
                <label for="nombre" class="col-md-3 control-label">Nombre</label>
                <div class="col-md-7">
                    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->contrato->getNombre() ?>">
                </div>
            </div>


            <!--
    <div class="form-group required">
        <label for="id_responsable" class="col-md-3 control-label">Responsable</label>
        <div class="col-md-7">
            <select id="id_responsable" name="id_responsable" class="form-control selectpicker" data-live-search="true" title="<?php echo ($view->contrato->getIdResponsable())? "": "Seleccione un responsable";     ?>">
                <option value = "<?php print $view->contrato->getIdResponsable() ?>">
                    <?php print $view->responsable; ?>
                </option>
            </select>
        </div>
    </div> -->

            <div class="form-group required">
                <label for="id_responsable" class="col-md-3 control-label">Responsable</label>
                <div class="col-md-7">
                    <select id="id_responsable" name="id_responsable" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable">
                        <?php foreach ($view->empleados as $em){
                            ?>
                            <option value="<?php echo $em['id_empleado']; ?>" <?php //echo ($em['fecha_baja'])? 'disabled' :'' ?>
                                <?php echo ($em['id_empleado'] == $view->contrato->getIdResponsable())? 'selected' :'' ?>
                                >
                                <?php echo $em['apellido'].' '.$em['nombre']; ?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
            </div>


            <div class="form-group required">
                <label class="col-md-3 control-label" for="fecha">Desde / hasta</label>
                <div class="col-md-7">

                    <div class="input-group input-daterange">
                        <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                        <div class="input-group-addon">a</div>
                        <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                    </div>

                </div>
            </div>


        </fieldset>



    <hr/>


        <div class="clearfix">
            <h4 class="pull-left">Empleados</h4>
            <button class="btn btn-default pull-right" id="add-empleado" >
                <span class="glyphicon glyphicon-plus dp_green"></span> Agregar empleado
            </button>
        </div>


    <div class="table-responsive" id="empleados-table">
        <table id="culin" class="table table-condensed dpTable table-hover">
            <thead>
            <tr>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>F. Desde</th>
                <th>F. Hasta</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <!-- se genera dinamicamente desde javascript -->
            </tbody>
        </table>
    </div>



    <hr/>





    <div id="myElem" clstyle="display:none"></div>


    </form>


    </div>





    <div class="panel-footer clearfix">
        <div class="button-group pull-right">
            <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
            <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
        </div>
    </div>











</div>




</div>

















<div class="col-md-2"></div>


