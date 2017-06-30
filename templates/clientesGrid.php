<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable();


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


    <h2>Clientes</h2>

    <div class="table-responsive">

        <div class="btn-group pull-right">
            <!--<button type="button" class="btn btn-primary btn-sm">Primary</button>-->
            <input id="new" class="btn btn-primary btn-sm" type="button" value="Nuevo Cliente">
        </div>
        <br/>
        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Peso</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->clientes as $cliente):  // uso la otra sintaxis de php para templates ?>
                <tr>
                    <td><?php echo $cliente['id'];?></td>
                    <td><?php echo $cliente['nombre'];?></td>
                    <td><?php echo $cliente['apellido'];?></td>
                    <td><?php echo $cliente['fecha_nac'];?></td>
                    <td><?php echo $cliente['peso'];?></td>
                    <td><a class="edit" href="javascript:void(0);" data-id="<?php echo $cliente['id'];?>">Editar</a></td>
                    <td><a class="delete" href="javascript:void(0);" data-id="<?php echo $cliente['id'];?>">Borrar</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>


<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el cliente?
    </div>

    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

    <div id="myElemento" style="display:none">


</div>





