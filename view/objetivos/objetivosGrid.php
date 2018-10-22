<script type="text/javascript">


    $(document).ready(function(){


        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/

            "fnInitComplete": function () {
                                $(this).show(); },

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

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Código</th>
                <th>Objetivo</th>
                <th>Puesto</th>
                <th>Área</th>
                <th>Contrato</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->objetivos)) {
                foreach ($view->objetivos as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_objetivo']; ?>">
                        <td><?php echo $rp['codigo']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['puesto']; ?></td>
                        <td><?php echo $rp['area']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td style="text-align: center"><?php //echo($rp['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>


                        <td class="text-center">
                            <!-- si tiene permiso para ver etapas -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('ETP_VER', array(1)) )? 'detalle' : 'disabled' ?>" href="javascript:void(0);">
                                <i class="far fa-list-alt fa-fw" title="detalle del objetivo"></i>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para clonar objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('ETP_VER', array(1)) )? 'clone' : 'disabled' ?>" href="javascript:void(0);">
                                <i class="far fa-clone fa-fw" title="clonar"></i>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para ver objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('ETP_VER', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open" title="ver" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit" title="editar" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; } ?>
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








