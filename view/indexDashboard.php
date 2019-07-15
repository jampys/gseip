<script type="text/javascript">


    $(document).ready(function(){



    });

</script>




<div class="row" style="height: 50px">




</div>


<div class="row">

    <div class="col-md-6">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Mis objetivos</div>
            <div class="panel-body">

                <?php //echo $_SESSION["id_empleado"]   ?>




                <!-- Table -->
                <table class="table">

                </table>




                <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Objetivo</th>
                        <th>Puesto</th>
                        <th>Resp. ejecución</th>
                        <th>Contrato</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if(isset($view->objetivos) && sizeof($view->objetivos) > 0) {
                        foreach ($view->objetivos as $rp):   ?>
                            <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                                id_objetivo="<?php echo $rp['id_objetivo'];?>"
                                cerrado="<?php echo $rp['cerrado']; ?>"
                                >
                                <td class="<?php echo ($rp['hijos']> 0 )? 'details-control' : ''; ?>"></td>
                                <td><span class="<?php echo ($rp['hijos']> 0 )? 'seleccionable' : ''; ?>"><?php echo $rp['codigo'];?></span></td>
                                <td><?php echo $rp['nombre']; ?></td>
                                <td><?php echo $rp['puesto']; ?></td>
                                <td><?php echo $rp['responsable_ejecucion']; ?></td>
                                <td><?php echo $rp['contrato']; ?></td>
                                <td>
                                    <div class="progress" style="margin-bottom: 0px">
                                        <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                            <?php echo $rp['progreso']; ?>%
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <!-- si tiene permiso para ver etapas -->
                                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'detalle' : 'disabled' ?>" href="javascript:void(0);">
                                        <i class="far fa-list-alt fa-fw dp_blue" title="detalle del objetivo"></i>
                                    </a>&nbsp;&nbsp;



                                    <!-- si tiene permiso para clonar objetivo -->
                                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-duplicate dp_blue" title="clonar">
                                    </a>&nbsp;&nbsp;



                                    <!-- si tiene permiso para ver objetivo -->
                                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;



                                    <!-- si tiene permiso para editar -->
                                    <a class="<?php echo (  !$rp['cerrado'] &&
                                        PrivilegedUser::dhasAction('OBJ_UPDATE', array(1))
                                    )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;



                                    <!-- si tiene permiso para eliminar -->
                                    <a class="<?php echo (  !$rp['cerrado'] &&
                                        PrivilegedUser::dhasAction('OBJ_DELETE', array(1))
                                    )? 'delete' : 'disabled' ?>" href="javascript:void(0);" title="borrar" >
                                        <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                                    </a>




                            </tr>
                        <?php endforeach; } ?>
                    </tbody>
                </table>











            </div>


        </div>


    </div>


    <div class="col-md-6"></div>











</div>










