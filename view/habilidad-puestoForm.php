<script type="text/javascript">


    $(document).ready(function(){

        var jsonPuestos = [];
        var jsonHabilidades = [];


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $("#myModal #search_puesto").autocomplete({ //ok
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

                $("#myModal #search_puesto").val('');

            }
        });



        $("#myModal #search_habilidad").autocomplete({
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

                    var arr = [ "one", "two", "three", "four", "five" ];

                    $('#habilidades-table tbody').append('<tr data-id='+item.id_habilidad+'>' +
                    '<td>'+item.nombre+'</td>' +
                    '<td>' +
                    '<select class="form-control" id="requerida-'+item.id_habilidad+'" name="requerida-'+item.id_habilidad+'">'+
                    '</select>'+
                    '</td>'+
                    '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                    '</tr>');

                    $.each(arr, function(i, itemx) {
                        $("#requerida-"+item.id_habilidad+"").append('<option value="">6</option>');
                    });
                }

                $("#myModal #search_habilidad").val('');
            }
        });



        $(document).on("click", "#puestos-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonPuestos[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        $(document).on("click", "#habilidades-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonHabilidades[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        $(document).one('click', '#myModal #submit',function(){
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
                                    <label for="search_habilidad" class="control-label">Habilidad</label>
                                    <input type="text" class="form-control" id="search_habilidad" name="search_habilidad" placeholder="Habilidad">
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="habilidades-table">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Requerida</th>
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

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



