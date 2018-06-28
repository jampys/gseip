<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">




                <?php if(isset($view->contratos) && sizeof($view->contratos) > 0) {?>

                    <table class="table table-condensed dataTable table-hover">
                        <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Nro.</th>
                            <th>F. afect.</th>
                            <th>F. desaf.</th>
                            <th>Puesto</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($view->contratos as $con): ?>
                            <tr data-id="<?php echo $con['id_contrato'];?>">
                                <td><?php echo $con['contrato'];?></td>
                                <td><?php echo $con['nro_contrato'];?></td>
                                <td><?php echo $con['fecha_desde'];?></td>
                                <td><?php echo $con['fecha_hasta'];?></td>
                                <td><?php echo $con['puesto'];?></td>
                                <td style="text-align: center"><?php echo($con['days_left'] < 0)? '<i class="fas fa-arrow-down fa-fw" style="color: #fc140c"></i>':'<i class="fas fa-arrow-up fa-fw" style="color: #49ed0e"></i>'; ?></td>


                                <!--<td class="text-center">
                                    <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_etapa'];?>" title="ver">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a class="<?php //echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) && $et['id_user'] == $_SESSION['id_user']  )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a class="<?php //echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)) && $et['id_user'] == $_SESSION['id_user']  )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </a>
                                </td>-->

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!--<table class="table table-condensed dataTable table-hover" id="puestos-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">Cod.</th>
                            <th class="col-md-10">Nombre</th>
                            <th class="col-md-1 text-center">Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>-->



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El empleado no se encuentra afectado a ningún contrato.
                    </div>

                <?php } ?>






            </div>

            <div class="modal-footer">
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>