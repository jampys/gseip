<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({

        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#empleado-form').validate({
            rules: {
                empleado: {required: true},
                puesto: {required: true},
                fecha_desde: {required: true}
            },
            messages:{
                empleado: "Seleccione un empleado",
                puesto: "Seleccione un puesto",
                fecha_desde: "Seleccione la fecha desde"
            }
        });


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        //al presionar boton de exportar
        $('#myModal').on("click", "#submit", function(){ //ok

            alert('presiono en exportar');

            params={};
            params.action = 'partes';
            params.operation = 'checkExportTxt';
            params.fecha_desde = $("#myModal #fecha_desde").val();
            params.fecha_hasta = $("#myModal #fecha_hasta").val();
            params.id_contrato = $("#myModal #id_contrato").val();



            $.post('index.php',params,function(data, status, xhr){

                //alert(xhr.responseText);

                if(data[0]['flag'] >=0){

                    $("#myElem").html(data[0]['msg']).addClass('alert alert-success').show();
                    //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                    //$("#msg-container").html('<div id="myElem" class="msg alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-check fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                    setTimeout(function() { $("#myElem").hide();
                                            //$('#myModal').modal('hide');
                     }, 2000);

                    throw new Error();
                    //params={};
                    //params.fecha_desde = $("#myModal #fecha_desde").val();
                    //params.fecha_hasta = $("#myModal #fecha_hasta").val();
                    //params.id_contrato = $("#myModal #id_contrato").val();
                    //location.href="index.php?action=sucesos&operation=txt";
                    location.href="index.php?action=sucesos&operation=txt&id_empleado="+params.id_empleado+"&eventos="+params.eventos+"&search_fecha_desde="+params.search_fecha_desde+"&search_fecha_hasta="+params.search_fecha_hasta+"&search_contrato="+params.search_contrato;
                    return false;

                }else{
                    $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').show();
                    //$("#msg-container").html('<div id="myElem" class="msg alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-exclamation-triangle fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                }

            }, 'json');









        });




    });

</script>





<!-- Modal -->
<fieldset <?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->empleado->getDomain() ) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="txt-form" id="txt-form" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

                    <div class="alert alert-info fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Esta pantalla permite exportar las novedades a txt.
                    </div>

                    <br/>


                    <div class="form-group required">
                        <label class="control-label" for="empleado">Fecha afectación / desafectación</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                        </div>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>







                </form>

                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Exportar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



