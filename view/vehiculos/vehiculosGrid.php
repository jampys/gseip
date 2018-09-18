<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            "order": [[5, "asc"], [0, "asc"]], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
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

    <h4>Vehículos</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-primary btn-sm" <?php echo ( PrivilegedUser::dhasAction('VEH_INSERT', array(1)) )? '' : 'disabled' ?> >Nuevo Vehículo</button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro.</th>
                <th>Matricula</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Fecha Baja</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->vehiculos as $vehiculo):   ?>
                <tr>
                    <td><?php echo $vehiculo['nro_movil'];?></td>
                    <td><?php echo $vehiculo['matricula'];?></td>
                    <td><?php echo $vehiculo['marca'];?></td>
                    <td><?php echo $vehiculo['modelo'];?></td>
                    <td><?php echo $vehiculo['modelo_ano'];?></td>
                    <td><?php echo $vehiculo['fecha_baja'];?></td>
                    <td class="text-center"><a class="contratos" href="javascript:void(0);" data-id="<?php echo $vehiculo['id_vehiculo'];?>" title="contratos"><i class="fas fa-suitcase"></i></a></td>
                    <td class="text-center"><a class="view" title="ver" href="javascript:void(0);" data-id="<?php echo $vehiculo['id_vehiculo'];?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="<?php echo (PrivilegedUser::dhasAction('VEH_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);" data-id="<?php echo $vehiculo['id_vehiculo'];?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="<?php echo (PrivilegedUser::dhasAction('VEH_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);" data-id="<?php echo $vehiculo['id_vehiculo'];?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el vehículo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








