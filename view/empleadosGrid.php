<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            responsive: true,
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "fnInitComplete": function () {
                $(this).show();
            },
            "stateSave": true,
            "order": [[5, "asc"], [0, "asc"]], //5=fecha_baja, 0=legajo
            columnDefs: [
                { responsivePriority: 1, targets: 7 }, //action buttons
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 0 ]}, //fecha_alta
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 0 ]} //fecha_baja
            ]
        });




    });

</script>

<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Empleados</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button class="btn btn-default" type="button" id="new" <?php echo ( PrivilegedUser::dhasAction('EMP_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Empleado
        </button>
    </div>

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Leg.</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>CUIL</th>
                <th>Fecha alta</th>
                <th>Fecha baja</th>
                <th>Lugar residencia</th>
                <th>Conv.</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->empleados as $empleado): ?>
                <tr data-id="<?php echo $empleado['id_empleado'];?>">
                    <td><?php echo $empleado['legajo'];?></td>
                    <td><?php echo $empleado['apellido'];?></td>
                    <td><?php echo $empleado['nombre'];?></td>
                    <td><?php echo $empleado['cuil'];?></td>
                    <td><?php echo $empleado['fecha_alta'];?></td>
                    <td><?php echo $empleado['fecha_baja'];?></td>
                    <td><?php echo $empleado['ciudad'];?></td>
                    <td><?php echo $empleado['convenio'];?></td>
                    <td class="text-center">
                        <a class="contratos" href="javascript:void(0);" title="detalles"><i class="fas fa-th-list dp_blue"></i></a>&nbsp;&nbsp;
                        <a class="view" href="javascript:void(0);" title="ver"><i class="far fa-sticky-note dp_blue"></i>&nbsp;&nbsp;
                        <a class="<?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar"><i class="fas fa-edit dp_blue"></i></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('EMP_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <!--</div>-->

</div>

<div class="col-md-1"></div>

