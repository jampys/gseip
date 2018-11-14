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

                    <h4><span class="label label-primary">Contratos</span></h4>

                    <table class="table table-condensed dataTable table-hover">
                        <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Ubicación</th>
                            <th>F. afect.</th>
                            <th>F. desaf.</th>
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
                                <td style="text-align: center">
                                    <?php echo($con['days_left'] < 0)? '<i class="fas fa-arrow-down fa-fw" style="color: #fc140c"></i><span style="color: #fc140c">'.abs($con['days_left']).'</span>' : '<i class="fas fa-arrow-up fa-fw" style="color: #49ed0e"></i><span style="color: #49ed0e">'.(($con['days_left'])? abs($con['days_left']) : "").'</span>'?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El vehículo no se encuentra afectado a ningún contrato. La funcionalidad se encuentra en construcción.
                                                                          Consulte con el administrador.
                    </div>

                <?php } ?>





                <br/>


                <?php if(isset($view->seguros) && sizeof($view->seguros) > 0) {?>

                    <h4><span class="label label-primary">Seguro vehicular</span></h4>

                    <div class="table-responsive fixedTable">

                        <table class="table table-condensed dataTable table-hover">
                            <thead>
                            <tr>
                                <th>Tipo seguro</th>
                                <th>Referencia</th>
                                <th>F. desde</th>
                                <th>F. hasta</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($view->seguros as $seg): ?>
                                <tr>
                                    <td><?php echo $seg['tipo_seguro']; ?></td>
                                    <td><?php echo $seg['referencia']; ?></td>
                                    <td><?php echo $seg['fecha_emision']; ?></td>
                                    <td><?php echo $seg['fecha_vencimiento']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                    
                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El vehículo no posee seguro vehicular.
                    </div>

                <?php } ?>






            </div>

            <div class="modal-footer">
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>