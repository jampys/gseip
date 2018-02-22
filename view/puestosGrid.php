<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            /*columnDefs: [
                        {targets: 1, render: $.fn.dataTable.render.ellipsis( 20)}

                        ]*/
            "fnInitComplete": function () {
                $(this).show(); }
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Puestos de trabajo</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-primary btn-sm" <?php echo ( PrivilegedUser::dhasAction('PUE_INSERT', array(1)) )? '' : 'disabled' ?> >Nuevo Puesto</button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Cod.</th>
                <th>Nombre</th>
                <th>Área</th>
                <th>Nivel competencia</th>
                <th>puesto superior</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->puestos as $puesto):   ?>
                <tr>
                    <td><?php echo $puesto['codigo'];?></td>
                    <td><?php echo $puesto['nombre'];?></td>
                    <td><?php echo $puesto['area'];?></td>
                    <td><?php echo $puesto['nivel_competencia'];?></td>
                    <td><?php echo $puesto['nombre_superior'];?></td>
                    <td class="text-center"><a class="edit" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="<?php echo (PrivilegedUser::dhasAction('PUE_DELETE', array(2)))? 'delete' : 'disabled'; ?>" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el puesto de trabajo?
    </div>

    <div id="myElem" style="display:none">

    </div>

</div>








