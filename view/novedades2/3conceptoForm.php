<style>



</style>



<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });



        

    });

</script>




<form name ="empleado-form" id="empleado-form" method="POST" action="index.php">




    <div class="alert alert-info">
        <strong><?php echo $view->label; ?></strong>
    </div>


    <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->
    <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php //print $view->empleado->getIdParteEmpleado() ?>">



    <div class="form-group">
        <!--<label class="control-label" for="id_empleado">Cuadrilla</label>-->
        <select id="id_cuadrilla" name="id_cuadrilla" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione una cuadrilla">
            <?php foreach ($view->cuadrillas as $cu){
                ?>
                <option value="<?php echo $cu['id_cuadrilla']; ?>"
                    <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                    >
                    <?php echo $cu['nombre']; ?>
                </option>
            <?php  } ?>
        </select>
    </div>

    <div class="form-group">
        <!--<label for="id_evento" class="control-label">Evento</label>-->
        <select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">
            <option value="">Seleccione un evento</option>
            <?php foreach ($view->eventos as $ar){ ?>
                <option value="<?php echo $ar['id_evento']; ?>"
                    <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                    >
                    <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                </option>
            <?php  } ?>
        </select>
    </div>


    <div class="form-group required">
        <div class="checkbox">
            <label>
                <input type="checkbox" id="conductor" name="conductor" <?php //echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Marcar la persona que maneja">Conductor</a>
            </label>
        </div>
    </div>

    <hr/>
    <!-- SECCION DE CONCEPTOS -->

    <div class="form-group">
        <label for="id_evento" class="control-label">Conceptos</label>
        <select class="selectpicker form-control show-tick" id="id_ruta" name="id_ruta" data-live-search="true" data-size="5">
            <option value="">Seleccione una ruta</option>
            <?php foreach ($view->eventos as $ar){ ?>
                <option value="<?php echo $ar['id_evento']; ?>"
                    <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                    >
                    <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                </option>
            <?php  } ?>
        </select>
    </div>



    <div class="row">


        <div class="form-group col-md-6">
            <!-- <label for="add_contrato" class="control-label">Nuevos partes</label>-->
            <select class="form-control selectpicker show-tick" id="add_contrato" name="add_contrato" data-live-search="true" data-size="5">
                <option value="">Seleccione un concepto</option>
                <?php foreach ($view->contratos as $con){
                    ?>
                    <option value="<?php echo $con['id_contrato']; ?>" >
                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>

        <div class="form-group col-md-3">
            <!-- <label for="id_periodo" class="control-label">&nbsp;</label>-->
            <input class="form-control" type="text" name="referencia" id="referencia" value = "<?php //print $view->renovacion->getReferencia() ?>" placeholder="Cantidad">
        </div>



        <div class="form-group col-md-3">
            <!--<label for="search">&nbsp;</label>-->
            <button type="submit" class="form-control btn btn-default" title="nuevo parte" id="new" <?php echo ( PrivilegedUser::dhasAction('PAR_INSERT', array(1)) )? '' : 'disabled' ?>>
                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
            </button>
        </div>


    </div>




    <div class="table-responsive" id="empleados-table">
        <table id="culin" class="table table-condensed dpTable table-hover">
            <thead>
            <tr>
                <th>Concepto</th>
                <th>Cant.</th>
                <th>Elim.</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <!-- se genera dinamicamente desde javascript -->
            </tbody>
        </table>
    </div>




    <hr/>



    <div class="form-group">
        <!--<label class="control-label" for="servicio">Comentario</label>-->
        <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario" rows="2"><?php //print $view->empleado->getComentario(); ?></textarea>
    </div>



</form>








