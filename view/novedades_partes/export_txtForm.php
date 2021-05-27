<style>
    #myElem{
        max-height: 150px ;
    }

    .alert-warning{
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }
</style>

<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#txt-form').validate({
            rules: {
                id_contrato: {required: true},
                periodo: {required: true}
            },
            messages:{
                id_contrato: "Seleccione un contrato",
                periodo: "Seleccione un período de liquidación"

            }
        });


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        /*$('#myModal').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "nov_periodos";
            params.operation = "getPeriodos";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();
            //params.activos = 1;

            $('#myModal #id_periodo').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["nombre"]+' ('+data[indice]["fecha_desde"]+' - '+data[indice]["fecha_hasta"]+')';
                            $("#myModal #id_periodo").append('<option value="'+data[indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data[indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data[indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');

                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        $('#myModal #id_periodo').selectpicker('refresh');

                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });*/


        //al presionar boton de exportar en txt
        $('#myModal').on("click", "#submit", function(){ //ok
            //alert('presiono en exportar');

            if ($("#txt-form").valid()){

                var params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.periodo = $("#myModal #periodo").val();
                //alert(params.id_contrato);

                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        $("#myElem").removeClass('alert-info').removeClass('alert-warning').removeClass('alert-danger');

                        if(data[0]['flag'] >=0){
                            $("#myElem").html(data[0]['msg']).addClass('alert alert-warning').addClass('pre-scrollable').show();
                        }
                        else{
                            $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').addClass('pre-scrollable').show();
                        }

                        location.href="index.php?action=partes&operation=exportTxt&id_contrato="+params.id_contrato+
                        "&periodo="+params.periodo;
                        return false;

                    },
                    /*error: function(data, textStatus, errorThrown) {
                     //alert(data.responseText);
                     $("#myElem").html('Error de conexión con la base de datos').addClass('alert alert-danger').show();
                     setTimeout(function() { $("#myElem").hide();
                     }, 2000);
                     },*/
                    beforeSend: function() {
                        $("#myElem").removeClass('alert-warning').removeClass('alert-danger');
                        $("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Verificando novedades y sucesos. Aguarde un instante...').addClass('alert alert-info').show();
                    }

                });



            }



            return false;
        });




        //Exportar novedades para control administracion
        $('#myModal').on("click", "#submit2", function(){

            if ($("#txt-form").valid()){

                var params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.first_contrato = params.id_contrato[0];
                params.periodo = $("#myModal #periodo").val();
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                //alert(params.first_contrato);


                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        $("#myElem").removeClass('alert-info').removeClass('alert-warning').removeClass('alert-danger');

                        if(data[0]['flag'] >=0){
                            $("#myElem").html(data[0]['msg']).addClass('alert alert-warning').addClass('pre-scrollable').show();
                        }
                        else{
                            $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').addClass('pre-scrollable').show();
                        }

                        setTimeout(function() {
                            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                            var URL="<?php echo $GLOBALS['ini']['application']['report_url']; ?>frameset?__report=gseip_nov_control_administracion_"+params.first_contrato+".rptdesign&p_id_contrato="+params.id_contrato+
                                "&p_id_periodo="+params.periodo+
                                "&p_id_user="+params.id_user;
                            var win = window.open(URL, "_blank");
                            return false;
                        }, 3000);


                    },
                    /*error: function(data, textStatus, errorThrown) {
                     //alert(data.responseText);
                     $("#myElem").html('Error de conexión con la base de datos').addClass('alert alert-danger').show();
                     setTimeout(function() { $("#myElem").hide();
                     }, 2000);
                     },*/
                    beforeSend: function() {
                        $("#myElem").removeClass('alert-warning').removeClass('alert-danger');
                        $("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Verificando novedades y sucesos. Aguarde un instante...').addClass('alert alert-info').show();
                    }

                });


            }

            return false;
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

                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Contrato</label>
                        <!--<select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">-->
                        <select multiple class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5" title="Seleccione un contrato">
                            <!--<option value="">Seleccione un contrato</option>-->
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <!--<div class="form-group required">
                        <label for="id_periodo" class="control-label">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un periodo" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript
                        </select>
                    </div>-->

                    <div class="form-group required">
                        <label class="control-label" for="periodo">Período de liquidación</label>
                        <select class="form-control selectpicker show-tick" id="periodo" name="periodo" data-live-search="true" data-size="5">
                            <option value="">Seleccione un período</option>
                            <?php foreach ($view->periodos_sup as $ps){
                                ?>
                                <option value="<?php echo $ps['periodo']; ?>" >
                                    <?php echo $ps['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags"></span>
                                &nbsp;<strong>Control de Novedades Administración:</strong>
                                Exporta novedades en formato de tabla cruzada (empleados/conceptos).
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit2" name="submit2" type="submit">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <span class="glyphicon glyphicon-tags" ></span>
                                &nbsp;<strong>Archivo de texto</strong>
                                <strong class="dp_orange">(RRHH)</strong>
                                <strong>:</strong>
                                Exporta novedades en formato .txt (admisible para BAS).
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit" name="submit" type="submit">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>


                </form>

                <div id="myElem" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit" title="txt">&nbsp;<i class="far fa-file-alt fa-lg"></i>&nbsp;</button>
                <button class="btn btn-primary" id="submit1" name="submit1" type="submit" title="pdf">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>-->
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



