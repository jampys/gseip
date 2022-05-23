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
            columnDefs: [
                { responsivePriority: 1, targets: 6 }
            ]
        });



    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">





    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Leg.</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>CUIL</th>
                <th>Habilidad</th>
                <th>F. desde</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->habilidadEmpleado)) {
                        foreach ($view->habilidadEmpleado as $he):   ?>
                            <tr data-id="<?php echo $he['id_habilidad_empleado']; ?>" >
                                <td><?php echo $he['legajo']; ?></td>
                                <td><?php echo $he['apellido']; ?></td>
                                <td><?php echo $he['nombre']; ?></td>
                                <td><?php echo $he['cuil']; ?></td>
                                <td><?php echo $he['habilidad']; ?></td>
                                <td><?php echo $he['fecha_desde']; ?></td>
                                <td class="text-center">
                                    <a class="view" title="ver" href="javascript:void(0);">
                                        <i class="far fa-sticky-note dp_blue"></i>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo (PrivilegedUser::dhasAction('HEM_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);">
                                        <i class="far fa-edit dp_blue"></i>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo (PrivilegedUser::dhasAction('HEM_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; } ?>
            </tbody>
        </table>

    <!--</div>-->

</div>

<div class="col-md-1"></div>

