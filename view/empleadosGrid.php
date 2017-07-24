<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false,
            //modal: true,
            buttons: [
                        {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar($('#confirm').data('id'));
                        },
                        class:"ui-button-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"ui-button-danger"
                    }

                    ]
        });


    });

</script>

<div class="col-md-2"></div>

<div class="col-md-8">

    <h3><strong>Empleados</strong></h3>

    <div style="text-align: right; margin-bottom: 10px">
        <button class="btn btn-primary btn-sm" type="button" id="new" >Nuevo Empleado</button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->empleados as $empleado): ?>
                <tr>
                    <td><?php echo $empleado['nombre'];?></td>
                    <td><?php echo $empleado['apellido'];?></td>
                    <td class="text-center"><a class="edit" href="javascript:void(0);" data-id="<?php echo $empleado['id_empleado'];?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="delete" href="javascript:void(0);" data-id="<?php echo $empleado['id_empleado'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-2"></div>


<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el cliente?
    </div>
</div>

    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

<div id="myElemento" style="display:none">

</div>





