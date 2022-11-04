﻿<style>
    #myElem{
        max-height: 150px ;
    }

    .alert-warning{
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }

    .alert{
        padding-top: 3px;
        padding-bottom: 3px;
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



        //RN03 Exportar novedades para control administracion
        $('#myModal').on("click", "#submit3", function(){

            if ($("#txt-form").valid()){

                var params={};
                params.action = 'partes';
                params.operation = 'checkExportTxt';
                params.id_contrato = $("#myModal #id_contrato").val();
                params.first_contrato = params.id_contrato[0];
                params.count_contrato = params.id_contrato.length;
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
                            //Efecto para hacer el scroll bottom
                            let h = $("#myElem").get(0).scrollHeight;
                            $("#myElem").animate({scrollTop: h});
                        }
                        else{
                            $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').addClass('pre-scrollable').show();
                        }

                        setTimeout(function() {
                            /*var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                            var URL="<?php echo $GLOBALS['ini']['application']['report_url']; ?>frameset?__report=gseip_nov_control_administracion_"+params.first_contrato+".rptdesign&p_id_contrato="+params.id_contrato+
                                "&p_id_periodo="+params.periodo+
                                "&p_id_user="+params.id_user;
                            var win = window.open(URL, "_blank");
                            return false;*/
                            let link = 'index.php?action=nov_reportes&operation=reporte_rn03'+
                                '&id_contrato='+$("#myModal #id_contrato").val()+
                                '&count_contrato='+params.count_contrato+
                                '&periodo='+$("#myModal #periodo").val();
                            window.location.href = link;
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
                        $("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Verificando novedades y sucesos...').addClass('alert alert-info').show();
                    }

                });


            }

            return false;
        });




        //RC01 Control de personal sin capacitacion
        $('#myModal').on("click", "#submit1", function(){ //ok

            if ($("#txt-form").valid()){

                let link = 'index.php?action=cap_reportes&operation=reporte_rc01'+
                    //'&id_contrato='+$("#myModal #id_contrato").val()+
                    '&periodo='+$("#myModal #periodo").val();
                window.location.href = link;
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
                        <label class="control-label" for="id_empleado">Período</label>
                        <!--<select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">-->
                        <select class="form-control" id="periodo" name="periodo">
                            <!--<option value="">Todos</option>-->
                            <?php foreach ($view->periodos as $pe){
                                ?>
                                <option value="<?php echo $pe['periodo']; ?>"
                                    <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                                    >
                                    <?php echo $pe['periodo']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>



                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <i class="fas fa-tags"></i>
                                &nbsp;<span class="label label-success">Nuevo</span>
                                &nbsp;<strong>RC01 Control de personal sin capacitación:</strong>
                                Personal que no recibió capacitación durante el período indicado.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit1" name="submit1" type="submit" title="Descargar reporte [xlsx]">&nbsp;<i class="fas fa-file-excel fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>



                    <!--<div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-sm-10">
                                <i class="fas fa-tags"></i>
                                &nbsp;<span class="label label-success">Nuevo</span>
                                &nbsp;<strong>RN07 Resumen de actividad</strong>
                                <strong class="dp_orange">(Gerencia)</strong>
                                <strong>:</strong>
                                Resumen de actividad de cuadrillas durante un período indicado.
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="submit7" name="submit7" type="submit" title="Descargar reporte [xlsx]">&nbsp;<i class="fas fa-file-excel fa-lg"></i>&nbsp;</button>
                            </div>
                        </div>
                    </div>-->


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



