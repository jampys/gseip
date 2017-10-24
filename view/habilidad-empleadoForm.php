<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        var jsonEmpleados = [];
        var jsonHabilidades = [];


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('#myModal #search_empleado').closest('.form-group').find(':input').on('keyup', function(e){ //ok
            //alert('hola');
            var code = (e.keyCode || e.which);
            if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                return;
            }

            var items="";

            $.ajax({
                url: "index.php",
                type: "post",
                dataType: "json",
                data: { "term": $(this).val(),  "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                success: function(data) {
                    $.each(data.slice(0, 5),function(index,item) { //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso

                        items+='<option value="'+item['cuil']+'"'+
                               ' id_empleado="'+item['id_empleado']+'"'+
                               ' legajo="'+item['legajo']+'"'+
                               ' >'+item['apellido']+' '+item['nombre']+ '</option>';

                    });

                    $("#myModal #search_empleado").html(items).selectpicker('refresh');

                }

            });

        });


        $("#myModal #search_empleado").on('changed.bs.select', function (e) {

                var selected = $("#myModal #search_empleado option:selected");

                item = {};
                item.id_empleado = selected.attr('id_empleado');
                item.empleado = selected.text();
                item.legajo = selected.attr('legajo');
                item.cuil = selected.val();

                if(jsonEmpleados[item.id_empleado]) {
                    //alert('el elemento existe');
                }
                else {
                    jsonEmpleados[item.id_empleado] =item;

                    $('#empleados-table tbody').append('<tr data-id='+item.id_empleado+'>' +
                    '<td>'+item.legajo+'</td>' +
                    '<td>'+item.empleado+'</td>' +
                    '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }

            });




        /*$("#myModal #search_habilidad").autocomplete({ //ok
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"habilidades", "operation":"autocompletarHabilidades"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nombre,
                                id_habilidad: item.id_habilidad

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
                item.nombre = ui.item.label;
                item.id_habilidad = ui.item.id_habilidad;

                if(jsonHabilidades[item.id_habilidad]) {
                    //alert('el elemento existe');
                }
                else {
                    jsonHabilidades[item.id_habilidad] =item;

                    $('#habilidades-table tbody').append('<tr data-id='+item.id_habilidad+'>' +
                    '<td>'+item.nombre+'</td>' +
                    '<td></td>' +
                    '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }

                $("#myModal #search_habilidad").val('');
            }
        });*/

        $('#myModal #search_habilidad').closest('.form-group').find(':input').on('keyup', function(e){ //ok
            //alert('hola');
            var code = (e.keyCode || e.which);
            if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                return;
            }

            var items="";

            $.ajax({
                url: "index.php",
                type: "post",
                dataType: "json",
                data: { "term": $(this).val(),  "action":"habilidades", "operation":"autocompletarHabilidades"},
                success: function(data) {
                    $.each(data.slice(0, 5),function(index,item) { //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso

                        items+='<option value="'+item['id_habilidad']+'"'+
                        ' >'+item['nombre']+'</option>';

                    });

                    $("#myModal #search_habilidad").html(items).selectpicker('refresh');

                }

            });

        });

        $("#myModal #search_habilidad").on('changed.bs.select', function (e) {

                var selected = $("#myModal #search_habilidad option:selected");

                item = {};
                item.id_habilidad = selected.val();
                item.nombre = selected.text();

                if(jsonHabilidades[item.id_habilidad]) {
                    //alert('el elemento existe');
                }
                else {
                    jsonHabilidades[item.id_habilidad] =item;

                    $('#habilidades-table tbody').append('<tr data-id='+item.id_habilidad+'>' +
                    '<td>'+item.nombre+'</td>' +
                    '<td></td>' +
                    '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }

            });



        $(document).on("click", "#empleados-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonEmpleados[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        $(document).on("click", "#habilidades-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonHabilidades[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        $(document).one('click', '#myModal #submit',function(){ //ok
            //alert(Object.keys(jsonEmpleados).length);
            if (Object.keys(jsonEmpleados).length > 0 && Object.keys(jsonHabilidades).length > 0){
                var params={};
                params.action = 'habilidad-empleado';
                params.operation = 'insert';


                var jsonEmpleadosIx = [];
                for ( var item in jsonEmpleados ){
                    jsonEmpleadosIx.push( jsonEmpleados[ item ] );
                }
                var jsonHabilidadesIx = [];
                for ( var item in jsonHabilidades ){
                    jsonHabilidadesIx.push( jsonHabilidades[ item ] );
                }

                params.vEmpleados = JSON.stringify(jsonEmpleadosIx);
                params.vHabilidades = JSON.stringify(jsonHabilidadesIx);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(data);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        $("#myElem").html('Habilidades empleados guardadas con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                        $("#search").trigger("click");
                    }else{
                        $("#myElem").html('Error al guardar las habilidades empleados').addClass('alert alert-danger').show();
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





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                    <div class="row">

                        <div class="col-md-6">

                            <form>

                                <div class="form-group col-md-12">
                                    <label for="search_empleado" class="control-label">Empleado</label>
                                    <select id="search_empleado" name="search_empleado" class="form-control selectpicker" data-live-search="true" title="Seleccione un empleado">
                                    </select>
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="empleados-table">
                                <thead>
                                <tr>
                                    <th class="col-md-1">Leg.</th>
                                    <th class="col-md-10">Empleado</th>
                                    <th class="col-md-1 text-center">Eliminar</th>
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
                                    <label for="search_habilidad" class="control-label">Habilidad</label>
                                    <select id="search_habilidad" name="search_habilidad" class="form-control selectpicker" data-live-search="true" title="Seleccione una habilidad">
                                    </select>
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="habilidades-table">
                                <thead>
                                <tr>
                                    <th class="col-md-9">Nombre</th>
                                    <th class="col-md-2">Puntos</th>
                                    <th class="col-md-1 text-center">Eliminar</th>
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

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



