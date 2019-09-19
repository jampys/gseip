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




                <?php if(isset($view->postulaciones) && sizeof($view->postulaciones) > 0) {?>

                    <h4><span class="label label-primary">Postulantes a la búsqueda</span></h4>


                    <div class="table-responsive fixedTable">

                        <!--<table class="table table-condensed dataTable table-hover">-->
                        <table class="table table-condensed dataTable table-hover">
                            <thead>
                            <tr>
                                <th>Postulante</th>
                                <th>Etapa</th>
                                <th>Aplica</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($view->postulaciones as $pos): ?>
                                <tr data-id="<?php echo $pos['id_postulacion'];?>">
                                    <td><?php echo $pos['postulante']; ?></td>
                                    <td><?php echo $pos['etapa']; ?></td>
                                    <td><?php echo($pos['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen candidatos para la búsqueda seleccionada. Para afectar un postulante a la búsqueda diríjase a
                        <?php if (true /*PrivilegedUser::dhasPrivilege('CON_VER', array(1))*/ ) { ?>
                            <a href="index.php?action=postulaciones">Postulaciones</a></p>
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