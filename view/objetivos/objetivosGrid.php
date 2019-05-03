<script type="text/javascript">


    $(document).ready(function(){


        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/

            /*"fnInitComplete": function () {
             $(this).show(); }*/

            "stateSave": true,
            columnDefs: [
                {targets: 1, render: $.fn.dataTable.render.ellipsis(30)} //https://datatables.net/blog/2016-02-26
                ,{ "width": "90px", "targets":5 } //progress bar
            ]

        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
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
                    <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                        cerrado="<?php echo $rp['cerrado']; ?>"
                        >
                        <td><?php echo $rp['codigo']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['puesto']; ?></td>
                        <td><?php echo $rp['area']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                    <?php echo $rp['progreso']; ?>%
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para ver etapas -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'detalle' : 'disabled' ?>" href="javascript:void(0);">
                                <i class="far fa-list-alt fa-fw" title="detalle del objetivo"></i>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para clonar objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>" href="javascript:void(0);">
                                <i class="far fa-clone fa-fw" title="clonar"></i>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para ver objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open" title="ver" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo (  !$rp['cerrado'] &&
                                                    PrivilegedUser::dhasAction('OBJ_UPDATE', array(1))
                                                 )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit" title="editar" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo (  !$rp['cerrado'] &&
                                                    PrivilegedUser::dhasAction('OBJ_DELETE', array(1))
                                                 )? 'delete' : 'disabled' ?>" href="javascript:void(0);" title="borrar" >
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>


                        </td>

                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    </div>

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








