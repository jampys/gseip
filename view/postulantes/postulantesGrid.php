<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            "order": [[0, "asc"], [1, "asc"]], // 1=apellido, 2=nombre
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
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Especialidad</th>
                <th>Ubicación</th>
                <th>Lista negra</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->postulantes)) {
                foreach ($view->postulantes as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_postulante']; ?>">
                        <td><?php echo $rp['apellido']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['dni']; ?></td>
                        <td><?php echo $rp['especialidad']; ?></td>
                        <td><?php echo $rp['ciudad']; ?></td>
                        <td style="text-align: center"><?php echo($rp['lista_negra'] == 1)? '<i class="fas fa-user-times fa-lg fa-fw" style="color: #fc140c"></i>' : ''; ?></td>

                        <td class="text-center">
                            <?php if($rp['cant_uploads']> 0 ){ ?>
                                <a href="#" title="<?php echo $rp['cant_uploads']; ?> adjuntos" >
                                    <i class="fas fa-paperclip"></i>
                                </a>
                            <?php } else{ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>&nbsp;&nbsp;

                            <a class="view" href="javascript:void(0);" title="ver">
                                <i class="far fa-sticky-note dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTE_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                <i class="far fa-edit dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTE_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <i class="far fa-trash-alt dp_red"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>




    <!--</div>-->

</div>

<div class="col-md-1"></div>



<div id="confirm">

</div>








