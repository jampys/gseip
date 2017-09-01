<script type="text/javascript">




    $(document).ready(function(){

        var jsonEmpleados = [];


        $.cargarTablaEmpleados=function(){

            $('#empleados-table tbody tr').remove();

            for (var i in jsonEmpleados) {

                if (jsonEmpleados[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }

                $('#empleados-table tbody').append('<tr id_empleado='+jsonEmpleados[i].id_empleado+'>' +
                //'<td>'+jsonEmpleados[i].empleado+'</td>' +
                '<td>'+jsonEmpleados[i].empleado+' '+jsonEmpleados[i].operacion+'</td>' +
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

        $('#contrato-form').validate({ //ok
            rules: {
                nro_contrato: {
                    required: true,
                    digits: true},
                compania: {required: true},
                responsable: {
                    require_from_group: {
                        param: [2, ".responsable-group"],
                        depends: function(element) { return $('#responsable').val().length > 0;}
                    }
                },
                fecha_desde: {required: true},
                fecha_hasta: {required: true}
            },
            messages:{
                nro_contrato: {
                    required: "Ingrese nro. de contrato",
                    digits: "Ingrese solo números"
                },
                compania: "Ingrese la compañía",
                responsable: "Seleccione un empleado sugerido",
                fecha_desde: "Seleccione la fecha desde",
                fecha_hasta: "Seleccione la fecha hasta"
            }

        });



        $("#responsable").autocomplete({ //ok
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
            change: function(event, ui) {
                $('#id_responsable').val(ui.item? ui.item.id : '');
                $('#responsable').val(ui.item.label);
            }
        });



        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_desde').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_hasta').datepicker('setStartDate', minDate);
                //$('#fecha_hasta').datepicker('setStartDate', minDate).datepicker('update', minDate);
        });

        $('#fecha_hasta').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_desde').datepicker('setEndDate', maxDate);
        });



        $('#contrato').on('click', '#add-empleado', function(e){ //ok
            //alert('insertar empleado');
            params={};
            params.action = "contratos";
            params.operation="addEmpleado";
            $('#popupbox1').load('index.php', params,function(){
                $('#myModal').modal();
                //alert('add empleado');
            });
            return false;
        });

        $('#contrato').on('click', '.update-empleado', function(e){ //ok
            //alert('actualizar empleado');
            var id = $(this).closest('tr').attr('id_empleado');
            //alert(id);
            params={};
            params.action = "contratos";
            params.operation="addEmpleado";
            $('#popupbox1').load('index.php', params,function(){
                $('#myModal').modal();
                $('#empleado').val(jsonEmpleados[id].empleado);
                $('#id_empleado').val(jsonEmpleados[id].id_empleado);
                $('#puesto').val(jsonEmpleados[id].id_puesto);
                $('#myModal #fecha_desde').datepicker('update', jsonEmpleados[id].fecha_desde );
                $('#myModal #fecha_hasta').datepicker('update', jsonEmpleados[id].fecha_hasta );

            });
            return false;
        });


        $(document).on('click', '#myModal #submit',function(){ //ok
            //Aqui se ingresa luego de insertar o actualizar un empleado
            //if ($("#contrato-form").valid()){

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

            //}
            return false;
        });


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


<div class="col-md-3"></div>

<div class="col-md-6">


<div class="panel panel-default" id="contrato">
    <div class="panel-heading"><h4><?php echo $view->label ?></h4></div>

    <div class="panel-body">




    <form class="form-horizontal" name ="contrato-form" id="contrato-form" method="POST" action="index.php">
    <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">

    <div class="form-group required">
        <label for="nro_contrato" class="col-md-4 control-label">Nro. Contrato</label>
        <div class="col-md-8">
            <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php print $view->contrato->getNroContrato() ?>">
        </div>
    </div>

    <div class="form-group required">
        <label for="compania" class="col-md-4 control-label">Compañía</label>
        <div class="col-md-8">
            <select class="form-control" id="compania" name="compania">
                <option value="">Seleccione la compañía</option>
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
    </div>


    <div class="form-group required">
        <label for="responsable" class="col-md-4 control-label">Responsable</label>
        <div class="col-md-8">
            <input type="text" class="form-control responsable-group" id="responsable" name="responsable" placeholder="Responsable" value ="<?php print $view->responsable; ?>">
            <input type="hidden" name="id_responsable" id="id_responsable" class="responsable-group" value = "<?php print $view->contrato->getIdResponsable() ?>" >
        </div>
    </div>


        <div class="form-group required">
            <label class="col-md-4 control-label" for="fecha">Desde / hasta</label>
            <div class="col-md-8">

                <div class="input-group input-daterange">
                    <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->contrato->getFechaDesde() ?>" placeholder="Fecha desde">
                    <div class="input-group-addon">a</div>
                    <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->contrato->getFechaHasta() ?>" placeholder="Fecha hasta">
                </div>

            </div>
        </div>



    <hr/>


        <div class="clearfix">
            <h4 class="pull-left">Empleados</h4>
            <button class="btn btn-primary btn-sm pull-right" id="add-empleado" >Agregar</button>
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

















<div class="col-md-3"></div>



