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


        //Al guardar una evaluacion de competencias
        $('#modalEac').on('click', '#submit',function(){
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
                    $(".modal-footer button").prop("disabled", true); //deshabilito botones
                    $("#myElem").html('Vencimientos guardados con exito').addClass('alert alert-success').show();
                    $("#search").trigger("click");
                    setTimeout(function() { $("#myElem").hide();
                                            $('#modalEac').modal('hide');
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <h4><span class="label label-primary">Contratos</span></h4>

                <?php if(isset($view->contratos) && sizeof($view->contratos) > 0) {?>

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





                <br/>

                <h4><span class="label label-primary">Vencimientos obligatorios</span></h4>


                <?php if(isset($view->vencimientos) && sizeof($view->vencimientos) > 0) {?>




                        <form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
                            <fieldset>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No tiene objetivos fijados para el período vigente.
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




                                <div id="myElem" class="msg" style="display:none"></div>


                                <br/>
                                <div id="footer-buttons" class="pull-right">
                                    <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
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