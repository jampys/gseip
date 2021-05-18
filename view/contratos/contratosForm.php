<script type="text/javascript">




    $(document).ready(function(){

        var t = $('#culin').DataTable({
            responsive: true,
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            "order": [[3, "asc"], [0, "asc"] ], //fecha desafec, legajo
            columnDefs: [
                {targets: 2, type: 'date-uk'}, //fecha afect
                {targets: 3, type: 'date-uk'}, //desafec
                {targets: 4, responsivePriority: 1, sortable: false }//botones
            ],
            language: {
                search: 'Buscar:'
            }

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
                '<td class="text-center"><a class="<?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? 'update-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="<?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? 'delete-empleado' : 'disabled' ?>" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
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
                    required: true},
                compania: {required: true},
                nombre: {required: true},
                id_responsable: {required: true},
                fecha_desde: {required: true},
                fecha_hasta: {required: true}
            },
            messages:{
                nro_contrato: {
                    required: "Ingrese nro. de contrato"
                },
                compania: "Ingrese la compañía",
                nombre: "Ingrese un nombre",
                id_responsable: "Seleccione un responsable",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"
            }

        });




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
            $('#content').load('index.php',{action:"contratos", operation:"refreshGrid"});
        });

        $('#contrato').on('click', '#back',function(){
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
                $('#id_empleado').selectpicker('val', jsonEmpleados[id].id_empleado).prop('disabled', true);
                $('#puesto').selectpicker('val', jsonEmpleados[id].id_puesto);
                $('#id_proceso').selectpicker('val', jsonEmpleados[id].id_proceso);
                $('#myModal #fecha_desde').val(jsonEmpleados[id].fecha_desde);
                $('#myModal #fecha_hasta').val(jsonEmpleados[id].fecha_hasta);
                $('#myModal #id_localidad').selectpicker('val', jsonEmpleados[id].id_localidad);
                $('.selectpicker').selectpicker('refresh'); //refresh de puesto y procesos
                $('#myModal').modal();

            });
            return false;
        });


        //Abre modal para ver datos del empleado en contrato
        $('#contrato').on('click', '.view-empleado', function(e){ //ok
            var id = $(this).closest('tr').attr('id_empleado');
            params={};
            params.action = "contratos";
            params.operation="loadEmpleado";
            params.target = "view";
            params.id_empleado = id;
            $('#popupbox1').load('index.php', params,function(){
                $('#id_empleado').selectpicker('val', jsonEmpleados[id].id_empleado).prop('disabled', true);
                $('#puesto').selectpicker('val', jsonEmpleados[id].id_puesto);
                $('#id_proceso').selectpicker('val', jsonEmpleados[id].id_proceso);
                $('#myModal #fecha_desde').val(jsonEmpleados[id].fecha_desde);
                $('#myModal #fecha_hasta').val(jsonEmpleados[id].fecha_hasta);
                $('#myModal #id_localidad').selectpicker('val', jsonEmpleados[id].id_localidad);
                $('.selectpicker').selectpicker('refresh'); //refresh de puesto y procesos
                $('#myModal').modal();
                //deshabilito campos
                //$("#empleado-form input, #empleado-form .selectpicker, #empleado-form textarea").prop("disabled", true);
                //$('.selectpicker').selectpicker('refresh');
                //$('.modal-footer').css('display', 'none');
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

            var id = $(this).closest('tr').attr('id_empleado');

            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar el empleado?<br/>Los cambios se hacen efectivos al Guardar</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){

                            if(!jsonEmpleados[id].id_empleado_contrato){ //si no esta en la BD
                                delete jsonEmpleados[id]; //lo elimina del array
                            }else{ //si esta en la BD, lo marca para eliminar
                                jsonEmpleados[id].operacion = 'delete';
                            }

                            dialog.find('.modal-footer').html('<div class="alert alert-success">Empleado eliminado con exito</div>');
                            setTimeout(function() {
                                dialog.modal('hide');
                                $.cargarTablaEmpleados();
                            }, 2000);

                            return false; //evita que se cierre automaticamente




                        }
                    }
                }
            });










            return false;
        });



        moment.locale('es');
        $('#fecha_desde, #fecha_hasta').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            parentEl: '#myModal',
            minDate: '01/01/2010',
            maxDate: '31/12/2029',
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
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

    <form name ="contrato-form" id="contrato-form" method="POST" action="index.php">

        <fieldset <?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? '' : 'disabled' ?>>

            <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">



            <div class="row">
                <div class="form-group col-md-6 required">
                    <label for="nro_contrato" class="control-label">Nro. Contrato</label>
                    <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php print $view->contrato->getNroContrato() ?>">
                </div>
                <div class="form-group col-md-6 required">
                    <label for="nombre" class="control-label">Nombre</label>
                    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value = "<?php print $view->contrato->getNombre() ?>">
                </div>
            </div>



            <div class="row">
                <div class="form-group col-md-6 required">
                    <label for="compania" class="control-label">Compañía</label>
                    <select class="form-control selectpicker show-tick" id="compania" name="compania" data-live-search="true" data-size="5" title="Seleccione la compañía">
                        <?php foreach ($view->companias as $cia){
                            ?>
                            <option value="<?php echo $cia['id_compania']; ?>"
                                <?php echo ($cia['id_compania'] == $view->contrato->getIdCompania())? 'selected' :'' ?>
                                >
                                <?php echo $cia['razon_social'];?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="form-group col-md-6 required">
                    <label for="id_responsable" class="control-label">Responsable</label>
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



            <div class="row">
                <div class="form-group col-md-6 required">
                    <label for="fecha_desde" class="control-label">Fecha desde</label>
                    <div class="inner-addon right-addon">
                        <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly>
                        <i class="glyphicon glyphicon-calendar"></i>
                    </div>
                </div>
                <div class="form-group col-md-6 required">
                    <label for="fecha_hasta" class="control-label">Fecha hasta</label>
                    <div class="inner-addon right-addon">
                        <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                        <i class="glyphicon glyphicon-calendar"></i>
                    </div>
                </div>
            </div>


        </fieldset>



    <hr/>


        <div class="clearfix">
            <h4 class="pull-left">Empleados</h4>
            <button class="btn btn-default pull-right" id="add-empleado"  <?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? '' : 'disabled' ?> >
                <span class="glyphicon glyphicon-plus dp_green"></span> Agregar empleado
            </button>
        </div>


    <div id="empleados-table">
        <table id="culin" class="table table-condensed dpTable table-hover dt-responsive nowrap">
            <thead>
            <tr>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>F. afec.</th>
                <th>F. desaf.</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <!-- se genera dinamicamente desde javascript -->
            </tbody>
        </table>
    </div>



    <hr/>





    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>


    </form>


    </div>





    <div class="panel-footer clearfix">
        <div class="button-group pull-right">
            <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->contrato->getDomain() ) && $view->target!='view' )? '' : 'disabled' ?> >Guardar</button>
            <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
        </div>
    </div>











</div>




</div>

















<div class="col-md-2"></div>



