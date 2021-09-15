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
                { responsivePriority: 1, targets: 4 }
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
                <th>Cod.</th>
                <th>Puesto</th>
                <th>Habilidad</th>
                <th>Excluyente</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->habilidadPuesto)) {
                        foreach ($view->habilidadPuesto as $hp):   ?>
                            <tr data-id="<?php echo $hp['id_habilidad_puesto']; ?>" >
                                <td><?php echo $hp['codigo']; ?></td>
                                <td><?php echo $hp['puesto']; ?></td>
                                <td><?php echo $hp['habilidad']; ?></td>
                                <td><?php echo $hp['requerida']; ?></td>
                                <td class="text-center">
                                    <a class="view" title="ver" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo ( PrivilegedUser::dhasAction('HPU_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" title="editar" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo ( PrivilegedUser::dhasAction('HPU_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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


