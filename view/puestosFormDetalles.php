<script type="text/javascript">

    $(document).ready(function(){


    });

</script>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">




                <?php if(isset($view->empleados) && sizeof($view->empleados) > 0) {?>

                    <h4><span class="label label-primary">Empleados en el puesto</span></h4>


                    <div class="table-responsive fixedTable">

                        <!--<table class="table table-condensed dataTable table-hover">-->
                        <table class="table table-condensed dataTable table-hover">
                            <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Contrato</th>
                                <th>F. desde</th>
                                <th>F. hasta</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($view->empleados as $em): ?>
                                <tr data-id="<?php echo $em['id_empleado'];?>">
                                    <td><?php echo $em['apellido'].' '.$em['nombre']; ?></td>
                                    <td><?php echo $em['contrato']; ?></td>
                                    <td><?php echo $em['fecha_desde']; ?></td>
                                    <td><?php echo $em['fecha_hasta']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen empleados en el puesto seleccionado. Para afectar un empleado a un puesto diríjase a
                        <?php if ( PrivilegedUser::dhasPrivilege('CON_VER', array(1)) ) { ?>
                            <a href="index.php?action=contratos">Contratos</a></p>
                        <?php } ?>
                    </div>

                <?php } ?>


                <br/>


                <?php if(isset($view->habilidades) && sizeof($view->habilidades) > 0) {?>

                    <h4><span class="label label-primary">Habilidades requeridas</span></h4>

                    <div class="table-responsive fixedTable">

                        <!--<table class="table table-condensed dataTable table-hover">-->
                        <table class="table table-condensed dataTable table-hover">
                            <thead>
                            <tr>
                                <th>Habilidad</th>
                                <th>Requerida</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($view->habilidades as $hab): ?>
                                <tr data-id="<?php echo $hab['id_habilidad'];?>">
                                    <td><?php echo $hab['habilidad']; ?></td>
                                    <td><?php echo $hab['requerida']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen habiliades registradas para el puesto seleccionado. Para hacerlo diríjase a
                        <?php if ( PrivilegedUser::dhasPrivilege('HPU_ABM', array(1)) ) { ?>
                            <a href="index.php?action=habilidad-puesto">Habilidades por puesto</a></p>
                        <?php } ?>
                    </div>

                <?php } ?>






            </div>

            <div class="modal-footer">
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>