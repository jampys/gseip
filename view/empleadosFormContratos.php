<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">




                <?php if(isset($view->contratos) && sizeof($view->contratos) > 0) {?>

                    <h4><span class="label label-primary">Contratos</span></h4>

                    <table class="table table-condensed dataTable table-hover">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>F. afect.</th>
                            <th>F. desaf.</th>
                            <th>Puesto</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($view->contratos as $con): ?>
                            <tr data-id="<?php echo $con['id_contrato'];?>">
                                <td><?php echo $con['contrato'].' '.$con['nro_contrato'];?></td>
                                <td><?php echo $con['localidad'];?></td>
                                <td><?php echo $con['fecha_desde'];?></td>
                                <td><?php echo $con['fecha_hasta'];?></td>
                                <td><?php echo $con['puesto'];?></td>
                                <td style="text-align: center">
                                    <?php echo($con['days_left'] < 0)? '<i class="fas fa-arrow-down fa-fw" style="color: #fc140c"></i><span style="color: #fc140c">'.abs($con['days_left']).'</span>' : '<i class="fas fa-arrow-up fa-fw" style="color: #49ed0e"></i><span style="color: #49ed0e">'.(($con['days_left'])? abs($con['days_left']) : "").'</span>'?>
                                </td>


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



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El empleado no se encuentra afectado a ningún contrato. Para afectar un empleado a un contrato diríjase a
                        <?php if ( PrivilegedUser::dhasPrivilege('CON_VER', array(1)) ) { ?>
                            <a href="index.php?action=contratos">Contratos</a></p>
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