<script type="text/javascript">


    $(document).ready(function(){

        var jsonEmpleados = [];


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $("#search_empleadox").autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": request.term, "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                apellido: item.apellido,
                                nombre: item.nombre,
                                legajo: item.legajo,
                                id: item.cuil,
                                id_empleado: item.id_empleado,
                                label: item.apellido+' '+item.nombre


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

                //$('#empleados-table tbody tr').each(function(){ $(this).remove(); });


                item = {};
                item.apellido = ui.item.apellido;
                item.nombre = ui.item.nombre;
                item.legajo = ui.item.legajo;
                item.cuil = ui.item.cuil;
                item.id_empleado = ui.item.id_empleado;



                if(jsonEmpleados[item.id_empleado]) {
                    //alert('el elemento existe');
                }
                else {
                    jsonEmpleados[item.id_empleado] =item;

                    $('#empleados-table tbody').append('<tr>' +
                    '<td>'+item.legajo+'</td>' +
                    '<td>'+item.apellido+'</td>' +
                    '<td>'+item.nombre+'</td>' +
                    '<td class="text-center"><a class="eliminar" href="#" data-id='+item.id_empleado+'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }

                /*for(var i in jsonEmpleados){
                    $('#empleados-table tbody').append('<tr>' +
                    '<td>'+jsonEmpleados[i].legajo+'</td>' +
                    '<td>'+jsonEmpleados[i].apellido+'</td>' +
                    '<td>'+jsonEmpleados[i].nombre+'</td>' +
                    '<td class="text-center"><a class="eliminar" href="#" data-id='+jsonEmpleados[i].id_empleado+'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>' +
                    '</tr>');
                }*/





            }
        });




        $(document).on("click",".eliminar",function(e){
            var index =  $(this).attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila



            //jsonObj.splice(index, 1);
            delete jsonEmpleados[index];





            e.preventDefault(); //para evitar que suba el foco al eliminar un plan

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

                            <form id="search_formx" name="search_formx">

                                <div class="form-group col-md-12">
                                    <label for="search_empleadox" class="control-label">Empleado</label>
                                    <input type="text" class="form-control" id="search_empleadox" name="search_empleadox" placeholder="Empleado">
                                    <input type="hidden" name="cuilx" id="cuilx" />
                                </div>

                            </form>
                            <br/>

                            <table class="table table-condensed dataTable table-hover" id="empleados-table">
                                <thead>
                                <tr>
                                    <th>Leg.</th>
                                    <th>Apellido</th>
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



