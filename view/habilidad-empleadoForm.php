<script type="text/javascript">


    $(document).ready(function(){


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
                                label: item.apellido+" "+item.nombre,
                                id: item.cuil

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
                //$('#cuilx').val(ui.item? ui.item.id : '');
                //$('#search_empleadox').val(ui.item.label);
            }
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

                                <div class="form-group col-md-10">
                                    <label for="search_empleadox" class="control-label">Empleado</label>
                                    <input type="text" class="form-control" id="search_empleadox" name="search_empleadox" placeholder="Empleado">
                                    <input type="hidden" name="cuilx" id="cuilx" />
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="search">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-primary btn-sm" id="new-empleadox">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </div>

                            </form>


                            <br/>




                                <table class="table table-condensed dataTable table-hover">
                                    <thead>
                                    <tr>
                                        <th>Leg.</th>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Eliminar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php //foreach ($view->domicilios as $dom):  ?>
                                        <tr>
                                            <td><?php //echo $dom['direccion'];?></td>
                                            <td><?php //echo $dom['CP'].' '.$dom['ciudad'].' '.$dom['provincia'];?></td>
                                            <td><?php //echo $dom['fecha_desde'];?></td>
                                            <td><?php //echo $dom['fecha_hasta'];?></td>
                                        </tr>
                                    <?php //endforeach; ?>
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



