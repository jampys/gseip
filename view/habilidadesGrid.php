<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            responsive: true,
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            "fnInitComplete": function () {
                $(this).show();
            },
            columnDefs: [
                { responsivePriority: 1, targets: 2 }
            ]
        });



    });

</script>


<div class="col-md-2"></div>

<div class="col-md-8">

    <h4>Habilidades</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo ( PrivilegedUser::dhasAction('HAB_INSERT', array(1)) )? '' : 'disabled' ?> >
            <i class="fas fa-plus dp_green"></i> Nueva Habilidad
        </button>
    </div>

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->habilidades as $habilidad):   ?>
                <tr>
                    <td><?php echo $habilidad['codigo'];?></td>
                    <td><?php echo $habilidad['nombre'];?></td>
                    <td class="text-center">
                        <a class="view" title="ver" href="javascript:void(0);" data-id="<?php echo $habilidad['id_habilidad'];?>"><i class="far fa-sticky-note dp_blue"></i></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('HAB_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);" data-id="<?php echo $habilidad['id_habilidad'];?>"><i class="far fa-edit dp_blue"></i></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('HAB_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);" data-id="<?php echo $habilidad['id_habilidad'];?>"><i class="far fa-trash-alt dp_red"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <!--</div>-->

</div>

<div class="col-md-2"></div>











