<script type="text/javascript">
    $(document).ready(function(){

        var jsonCompetencias = [];

        // Al presionar alguno de los select de puntajes
        $(':checkbox').on('click', function(e){
            //Solo guarda en el array los elementos que cambiaron, no es necesario tener los que vienen de la BD.
            item = {};
            item.id_empleado_vencimiento = $(this).val();
            item.id_empleado = $('#id_empleado').val();
            item.id_vencimiento = $(this).attr('id_vencimiento');
            item.operation = '';


            if(jsonCompetencias[item.id_vencimiento]) { //lo modifica
                if(this.checked) jsonCompetencias[item.id_vencimiento].operation = 'checked';
                else jsonCompetencias[item.id_vencimiento].operacion = 'unchecked';
            }
            else { //si no existe, lo agrega
                jsonCompetencias[item.id_vencimiento] = item;
                if(this.checked) jsonCompetencias[item.id_vencimiento].operation = 'checked';
                else jsonCompetencias[item.id_vencimiento].operation = 'unchecked';
            }

        });


        //Al guardar los vencimientos
        $('#myModal').on('click', '#submit',function(){
            //alert('guardar evaluacion desempeño');
            //if ($("#eac-form").valid()){
            var params={};
            params.action = 'empleados';
            params.operation = 'saveVencimientos';
            //params.periodo = $('#periodo').val();
            //params.cerrado = $('#cerrado').val();
            //alert(params.id_compania);

            var jsonCompetenciasIx = $.map(jsonCompetencias, function(item){ return item;} );
            params.vCompetencias = JSON.stringify(jsonCompetenciasIx);


            $.post('index.php',params,function(data, status, xhr){
                //No se usa .fail() porque el resultado viene de una transaccion (try catch) que siempre devuelve 1 o -1
                //alert(xhr.responseText);
                if(data >=0){
                    $("#myModal button").prop("disabled", true); //deshabilito botones
                    $("#myElem").html('Vencimientos guardados con exito').addClass('alert alert-success').show();
                    $("#search").trigger("click");
                    setTimeout(function() { $("#myElem").hide();
                        //$('#modalEac').modal('hide');
                        $("#myModal button").prop("disabled", false); //habilito botones
                    }, 2000);

                }else{
                    $("#myElem").html('No es posible guardar los vencimientos').addClass('alert alert-danger').show();
                }

            }, 'json');

            //}
            return false;
        });




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





                <br/>

                <h4><span class="label label-primary">Vencimientos obligatorios</span></h4>


                <?php if(isset($view->vencimientos) && sizeof($view->vencimientos) > 0) {?>




                    <form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
                        <fieldset>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Seleccionar los vencimientos requeridos para el empleado.
                            </div>

                            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->empleado->getIdEmpleado() ?>">

                            <div class="table-responsive fixedTable">

                                <?php foreach ($view->vencimientos as $v): ?>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="<?php echo $v['id_empleado_vencimiento'] ?>" <?php echo ($v['id_empleado_vencimiento'])? 'checked' : ''; ?>
                                                   id_vencimiento="<?php echo $v['id_vencimiento'] ?>"
                                                >

                                            <?php echo $v['nombre']; ?>
                                        </label>
                                    </div>

                                <?php endforeach; ?>

                            </div>



                            <br/>

                            <div id="myElem" class="msg" style="display:none"></div>


                            <div id="footer-buttons" class="pull-right">
                                <button class="btn btn-primary" id="submit" name="submit" type="button" <?php echo ( PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '' : 'disabled' ?> >Guardar</button>
                                <!--<button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>-->
                            </div>


                        </fieldset>
                    </form>



                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No es posible cargar los vencimientos obligatorios del empleado.
                    </div>

                <?php } ?>






            </div>

            <div class="modal-footer">
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>