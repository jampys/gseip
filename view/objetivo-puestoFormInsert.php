<script type="text/javascript">


    $(document).ready(function(){

        var jsonPuestos = [];
        var jsonObjetivos = [];


        $.cargarTablaObjetivos=function(){
            //alert('cargar tabla objetivos');

            $('#objetivos-table tbody tr').remove();

            for (var i in jsonObjetivos) {


                $('#objetivos-table tbody').append('<tr id_objetivo='+jsonObjetivos[i].id_objetivo+'>' +
                '<td>'+jsonObjetivos[i].objetivo+'</td>' +
                    //'<td>'+jsonEmpleados[i].empleado+' '+jsonEmpleados[i].operacion+'</td>' +
                '<td class="text-center"><a class="update" href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>' +
                '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                '</tr>');

            }

        };


        $("#objetivo-puesto #search_puesto").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"puestos", "operation":"autocompletarPuestos"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                codigo: item.codigo,
                                id_puesto: item.id_puesto,
                                label: item.nombre


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

                item = {};
                item.codigo = ui.item.codigo;
                item.id_puesto = ui.item.id_puesto;
                item.nombre = ui.item.label;

                if(jsonPuestos[item.id_puesto]) {
                    //alert('el elemento existe');
                }
                else {
                    jsonPuestos[item.id_puesto] =item;

                    $('#puestos-table tbody').append('<tr data-id='+item.id_puesto+'>' +
                    '<td>'+item.codigo+'</td>' +
                    '<td>'+item.nombre+'</td>' +
                    '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }

                $("#objetivo-puesto #search_puesto").val('');

            }
        });



        $("#objetivo-puesto #search_objetivo").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"objetivos", "operation":"autocompletarObjetivos"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nombre,
                                id_objetivo: item.id_objetivo

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
                    params.operation="loadObjetivo";
                    $('#popupbox').load('index.php', params,function(){
                        $('#myModalUpdate').modal();
                        //$('#myModalUpdate #objetivo').val($('#objetivo-puesto #search_objetivo').val());
                        //$('#myModalUpdate #id_objetivo').val($('#objetivo-puesto #id_objetivo').val());
                        $('#myModalUpdate #objetivo').val(ui.item.label);
                        $('#myModalUpdate #id_objetivo').val(ui.item.id_objetivo);



                    });
                    return false;



                $("#objetivo-puesto #search_objetivo").val('');
            }
        });





        //Guarda los cambios luego de insertar o actualizar un empleado del contrato
        $(document).on('click', '#myModalUpdate #submit',function(){ //ok
            //alert($('#myModalUpdate #id_objetivo').val());

            //if ($("#empleado-form").valid()){

                var id = $('#myModalUpdate #id_objetivo').val();

                if(jsonObjetivos[id]) { //si ya existe en el array, lo actualiza
                    //alert('el elemento ya existe');
                    jsonObjetivos[id].valor = $('#myModalUpdate #valor').val();
                    //jsonEmpleados[id].puesto = $("#puesto option:selected").text();

                }
                else { // si no existe en el array, lo inserta
                    item = {};
                    item.id_objetivo = id;
                    item.objetivo = $('#myModalUpdate #objetivo').val();
                    item.valor = $('#myModalUpdate #valor').val();
                    item.operacion = 'insert';
                    jsonObjetivos[id] = item;
                    //alert('agregado con exito');
                }

                $.cargarTablaObjetivos();

            //}
            return false;
        });


        //Abre modal para actualizar el objetivo
        $('#objetivos-table').on('click', '.update', function(e){ //ok
            //alert('actualizar objetivo');
            var id = $(this).closest('tr').attr('id_objetivo');
            //alert(id);
            params={};
            params.action = "objetivo-puesto";
            params.operation="loadObjetivo";
            $('#popupbox').load('index.php', params,function(){
                $('#myModalUpdate').modal();
                $('#myModalUpdate #id_objetivo').val(jsonObjetivos[id].id_objetivo);
                $('#myModalUpdate #objetivo').val(jsonObjetivos[id].objetivo);
                $('#myModalUpdate #valor').val(jsonObjetivos[id].valor);


            });
            return false;
        });



        $('#puestos-table').on('click',  '.delete', function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonPuestos[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento
        });


        $('#objetivos-table').on('click',  '.delete', function(e){ //ok
            var index =  $(this).closest('tr').attr('id_objetivo');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonObjetivos[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento
        });


        $('#objetivo-puesto').on('click', '#submit',function(){
            //alert(Object.keys(jsonPuestos).length);
            if (Object.keys(jsonPuestos).length > 0 && Object.keys(jsonObjetivos).length > 0){
                var params={};
                params.action = 'objetivo-puesto';
                params.operation = 'insert';
                params.periodo = $('#objetivo-puesto #periodo').val();
                params.id_contrato = $('#objetivo-puesto #contrato').val();
                //alert(params.periodo);

                var jsonPuestosIx = [];
                for ( var item in jsonPuestos ){
                    jsonPuestosIx.push( jsonPuestos[ item ] );
                }
                var jsonObjetivosIx = [];
                for ( var item in jsonObjetivos ){
                    jsonObjetivosIx.push( jsonObjetivos[ item ] );
                }

                params.vPuestos = JSON.stringify(jsonPuestosIx);
                params.vHabilidades = JSON.stringify(jsonObjetivosIx);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        $("#myElem").html('Objetivos puestos guardados con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                        $("#search").trigger("click");
                    }else{
                        $("#myElem").html('Error al guardar los objetivos puestos').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                                            $('#myModal').modal('hide');
                                        }, 2000);

                });

            }
            return false;
        });








    });

</script>





<div class="col-md-1"></div>


<div class="col-md-10">


    <div class="panel panel-default" id="objetivo-puesto">
        <div class="panel-heading"><h4><?php echo $view->label ?></h4></div>

        <div class="panel-body">




                <div class="row">

                    <div class="form-group col-md-2">

                        <select class="form-control" id="periodo" name="periodo">
                            <?php foreach ($view->periodos as $pe){
                                ?>
                                <option value="<?php echo $pe; ?>"
                                    <?php echo ($pe == $view->periodo_actual   )? 'selected' :'' ?>
                                    >
                                    <?php echo $pe; ?>
                                </option>
                            <?php  } ?>
                        </select>

                    </div>

                    <div class="form-group col-md-3">

                        <select class="form-control" id="contrato" name="contrato">
                            <option value="">Todos</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>"
                                    <?php //echo ($pe == $view->periodo_actual   )? 'selected' :'' ?>
                                    >
                                    <?php echo $con['nro_contrato'].' - '.$con['compania']; ?>
                                </option>
                            <?php  } ?>
                        </select>

                    </div>

                    <div class="col-md-7"></div>

                </div>



                <div class="row">

                        <div class="col-md-6">

                            <form>

                                <div class="form-group col-md-12">
                                    <label for="search_puesto" class="control-label">Puesto</label>
                                    <input type="text" class="form-control" id="search_puesto" name="search_puesto" placeholder="Puesto">
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="puestos-table">
                                <thead>
                                <tr>
                                    <th>Cod.</th>
                                    <th>Nombre</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- se genera dinamicamente con javascript -->
                                </tbody>
                            </table>



                        </div>


                        <div class="col-md-6">


                            <form>

                                <div class="form-group col-md-12">
                                    <label for="search_objetivo" class="control-label">Objetivo</label>
                                    <input type="text" class="form-control" id="search_objetivo" name="search_objetivo" placeholder="Objetivo">
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="objetivos-table">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th class="text-center">Editar</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- se genera dinamicamente con javascript -->
                                </tbody>
                            </table>


                        </div>


                    </div>






                <div id="myElem" style="display:none"></div>





</div>



<div class="panel-footer clearfix">
    <div class="button-group pull-right">
        <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
        <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>
</div>





</div>




</div>



<div class="col-md-1"></div>





