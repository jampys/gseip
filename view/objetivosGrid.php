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


<div class="col-md-1"></div>

<div class="col-md-10">


    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>objetivo superior</th>
                <th>Tipo</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->objetivos as $objetivo):   ?>
                <tr>
                    <td><?php echo $objetivo['nombre'];?></td>
                    <td><?php echo $objetivo['objetivo_superior'];?></td>
                    <td><?php echo $objetivo['tipo'];?></td>
                    <td class="text-center"><a class="edit" href="javascript:void(0);" data-id="<?php echo $objetivo['id_objetivo'];?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="delete" href="javascript:void(0);" data-id="<?php echo $objetivo['id_objetivo'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








