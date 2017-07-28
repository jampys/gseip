<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<div class="col-md-2"></div>

<div class="col-md-8">

    <h3>Clientes</h3>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-primary btn-sm">Nuevo Cliente</button>
    </div>

    <div class="table-responsive">

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
                    <td class="text-center"><a class="edit" href="javascript:void(0);" data-id="<?php echo $cliente['id'];?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="delete" href="javascript:void(0);" data-id="<?php echo $cliente['id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
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

    <div id="myElemento" style="display:none">

    </div>

</div>








