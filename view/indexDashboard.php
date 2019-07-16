<script type="text/javascript">


    $(document).ready(function(){



    });

</script>




<div class="row" style="height: 50px">




</div>


<div class="row">

    <div class="col-md-9">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Mis objetivos</div>
            <div class="panel-body">

                <?php //echo $_SESSION["id_empleado"]   ?>




                <!-- Table -->
                <table class="table">

                </table>


                <?php if(isset($view->objetivos) && sizeof($view->objetivos) > 0) { ?>

                <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Objetivo</th>
                        <th>Resp. ejecución</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php foreach ($view->objetivos as $rp):   ?>
                            <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                                id_objetivo="<?php echo $rp['id_objetivo'];?>"
                                cerrado="<?php echo $rp['cerrado']; ?>"
                                >
                                <td class="<?php echo ($rp['hijos']> 0 )? 'details-control' : ''; ?>"></td>
                                <td><span class="<?php echo ($rp['hijos']> 0 )? 'seleccionable' : ''; ?>"><?php echo $rp['codigo'];?></span></td>
                                <td><?php echo $rp['nombre']; ?></td>
                                <td><?php echo $rp['responsable_ejecucion']; ?></td>
                                <td>
                                    <div class="progress" style="margin-bottom: 0px">
                                        <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                            <?php echo $rp['progreso']; ?>%
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach;  ?>
                    </tbody>
                </table>


                <?php }else{ ?>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen empleados en el puesto seleccionado. Para afectar un empleado a un puesto diríjase a
                        <?php if ( PrivilegedUser::dhasPrivilege('CON_VER', array(1)) ) { ?>
                            <a href="index.php?action=contratos">Contratos</a></p>
                        <?php } ?>
                    </div>

                <?php } ?>











            </div>


        </div>


    </div>


    <div class="col-md-3"></div>











</div>










