<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        /*
        $('.input-daterange').datepicker({ //ok
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true,
            clearBtn: true
        }).on('changeDate', function(){
            //calcula la diferencia en dias entre las 2 fechas
            var minDate = $(this).closest('.row').find('.cfd').datepicker('getDate');
            var maxDate = $(this).closest('.row').find('.cfh').datepicker('getDate');
            //maxDate - minDate devuelve la diferencia en milisegundos. 86400 = cant de seg por dia. X 1000 da los miliseg por dia.
            if(minDate == null || maxDate == null) $(this).closest('.row').find('.cdias').val(0);
            else $(this).closest('.row').find('.cdias').val((maxDate - minDate)/(86400*1000)+1);

        });

        //solo ocurre al cambiar el valor de fecha_desde y fecha_hasta. Restringe el rango de fechas de fd1, fh1, fd2, fh2
        $('#fecha_desde, #fecha_hasta').on('changeDate', function(){
            var fecha_desde = $('#myModal #fecha_desde').val();
            var fecha_hasta = $('#myModal #fecha_hasta').val();
            $('#fd1, #fd2').datepicker('setStartDate', fecha_desde);
            $('#fh1, #fh2').datepicker('setEndDate', fecha_hasta);
        });


        //Sirve para cuando se trata de una edicion. Restringe las fd1, fh1, fd2, fh2
        $("#fecha_desde").trigger("changeDate");


        //Al hacer check o uncheck en checkbox
        $("#chk_imputar").change(function() {
            var ischecked= $(this).is(':checked');
            if(ischecked) {
                //alert('uncheckd ' + $(this).val());
                $('#fd1').datepicker('update', $('#myModal #fecha_desde').val());
                $('#fh1').datepicker('update', $('#myModal #fecha_hasta').val());
                $('#cantidad1').val($('#dias').val());
                $('#id_periodo2').val("").selectpicker('refresh');
                $('#fd2').val("");
                $('#fh2').val("");
                $('#cantidad2').val(0);
            }else{
                $('#fd1').val("");
                $('#fh1').val("");
                $('#cantidad1').val(0);
            }

        });
        */


        moment.locale('es');
        $('#fecha, #f1, #f2').daterangepicker({
            parentEl: '#myModal',
            //showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            linkedCalendars: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
            picker.element.valid();
            $(this).closest('.row').find('.cdias').val(picker.endDate.diff(picker.startDate, 'days')+1);
        });

        var drp = $('#fecha').data('daterangepicker');
        var drp1 = $('#f1').data('daterangepicker');
        var drp2 = $('#f2').data('daterangepicker');

        //solo ocurre al cambiar el valor de fecha. Restringe el rango de fechas de f1 y f2
        $('#fecha').on("apply.daterangepicker", function (e, picker) {
            drp1.minDate = picker.startDate;
            drp1.maxDate = picker.endDate;
            drp2.minDate = picker.startDate;
            drp2.maxDate = picker.endDate;
        });

        //Sirve para restringir f1 y f2 al rango de fechas de fecha.
        drp1.minDate = drp.startDate;
        drp1.maxDate = drp.endDate;
        drp2.minDate = drp.startDate;
        drp2.maxDate = drp.endDate;

        //Al hacer check o uncheck en checkbox
        $("#chk_imputar").change(function() {
            var ischecked= $(this).is(':checked');
            if(ischecked) {
                drp1.setStartDate(drp.startDate);
                drp1.setEndDate(drp.endDate);
                drp1.element.val(drp1.startDate.format(drp1.locale.format) + ' - ' + drp1.endDate.format(drp1.locale.format));
                $('#cantidad1').val($('#dias').val());
                $('#id_periodo2').val("").selectpicker('refresh');
                drp2.setStartDate(new Date); //limpia starDate
                drp2.setEndDate(new Date); //limpia endDate
                $('#f2').val(""); //limpia el input
                $('#cantidad2').val(0);
            }else{
                drp1.setStartDate(new Date);
                drp1.setEndDate(new Date);
                $('#f1').val("");
                $('#cantidad1').val(0);
            }

        });



        $('.image').viewer({});

        var objeto={};


        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploadsSucesos&operation=upload",
            dragDrop: <?php echo ( PrivilegedUser::dhasAction('SUC_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: <?php echo ( PrivilegedUser::dhasAction('SUC_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            showDownload:true,
            showCancel: true,
            showAbort: true,
            allowDuplicates: false,
            allowedTypes: "jpg, png, pdf, txt, doc, docx",

            dynamicFormData: function(){
                var data ={ "id": ($('#id_suceso').val())? $('#id_suceso').val() : objeto.id };
                return data;},

            maxFileSize:2097152, //tamaño expresado en bytes
            showPreview:true,
            previewHeight: "75px",
            previewWidth: "auto",
            uploadQueueOrder:'bottom', //el orden en que se muestran los archivos subidos.
            showFileCounter: false, //muestra el nro de archivos subidos
            downloadStr: "<i class='fas fa-download'></i>",
            deleteStr: "<span class='glyphicon glyphicon-trash'></span>",
            dragDropStr: "<span><b>Arrastrar &amp; Soltar</b></span>",
            uploadStr:"<span class='glyphicon glyphicon-plus'></span> Subir",
            cancelStr: "<i class='fas fa-minus-square'></i>",

            extErrorStr: "no está permitido. Solo se permiten extensiones: ",
            duplicateErrorStr: "no permitido. El archivo ya existe.",
            sizeErrorStr: "no permitido. Tamaño máximo permitido: ",

            onLoad:function(obj){
                $.ajax({
                    cache: false,
                    url: "index.php",
                    data:{"action": "uploadsSucesos", "operation": "load", "id": $('#id_suceso').val() },
                    type:"post",
                    dataType: "json",
                    success: function(data) {

                        //alert('todo ok '+data);
                        for(var i=0;i<data.length;i++) {
                            if(data[i]['jquery-upload-file-error']) {
                                //alert('encontro el error');
                                obj.dpErrorOnLoad(data[i]["name"], data[i]['jquery-upload-file-error']);
                            }
                            else{
                                obj.createProgress(data[i]["name"],data[i]["path"],data[i]["size"], data[i]["fecha"]);
                            }

                        }

                        $('#myModal img').addClass('image').css('cursor', 'zoom-in');
                        $('.image').viewer({});

                    },
                    error: function(e) {
                        alert('errrorrrr '+ e.responseText);
                    }

                });
            },
            deleteCallback: function (data, pd) {
                for (var i = 0; i < data.length; i++) {
                    $.post("index.php", {action: "uploadsSucesos", operation: "delete", name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploadsSucesos&operation=download&filename="+filename;
            },
            afterUploadAll:function(obj) {
                //You can get data of the plugin using obj
                closeFormSuccess();
            }
        });



        //Select dependiente: al seleccionar contrato carga periodos vigentes
        // solo se usa cuando es un insert
        $('#suceso-form').on('change', '#id_empleado', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "nov_periodos";
            params.operation = "getPeriodos1";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_empleado = $('#myModal #id_empleado').val();
            params.activos = 1;

            $('#id_periodo1, #id_periodo2').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    $("#id_periodo1, #id_periodo2").html('<option value="">Seleccione un período</option>');

                    if(Object.keys(data).length > 0){

                        $.each(data, function(indice, val){
                            var label = data[indice]["nombre"]+' ('+data[indice]["fecha_desde"]+' - '+data[indice]["fecha_hasta"]+')';
                            $("#id_periodo1, #id_periodo2").append('<option value="'+data[indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data[indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data[indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');

                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        $('#id_periodo1, #id_periodo2').selectpicker('refresh');

                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });




        $('#myModal').on('click', '#submit',function(){ //ok


            if ($("#suceso-form").valid()){

                var params={};
                params.action = 'sucesos';
                params.operation = 'saveSuceso';
                params.id_suceso = $('#myModal #id_suceso').val();
                params.id_empleado = $('#myModal #id_empleado').val();
                params.id_evento = $('#myModal #id_evento').val();
                params.periodo = $('#myModal #periodo').val();
                params.fecha_desde = drp.startDate.format('DD/MM/YYYY');
                params.fecha_hasta = drp.endDate.format('DD/MM/YYYY');
                params.observaciones = $('#myModal #observaciones').val();
                params.id_periodo1 = $('#myModal #id_periodo1').val();
                params.cantidad1 = $('#myModal #cantidad1').val();
                params.id_periodo2 = $('#myModal #id_periodo2').val();
                params.cantidad2 = $('#myModal #cantidad2').val();
                params.fd1 = drp1.startDate.format('DD/MM/YYYY');
                params.fh1 = drp1.endDate.format('DD/MM/YYYY');
                params.fd2 = (drp2.element.val())? drp2.startDate.format('DD/MM/YYYY'): '';
                params.fh2 = (drp2.element.val())? drp2.endDate.format('DD/MM/YYYY'): '';
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        if(uploadObj.dpCounter() >= 1) { uploadObj.startUpload(); } //se realiza el upload solo si el formulario se guardo exitosamente
                        else closeFormSuccess();
                    }else{
                        $("#myElem").html('Error al guardar el suceso').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });

        function closeFormSuccess(){
            $("#myModal #myElem").html('Suceso guardado con exito').addClass('alert alert-success').show();
            setTimeout(function() { $("#myElem").hide();
                                    //$("#suceso-form #cancel").trigger("click"); //para la modal (nov2)
                                    $('#myModal').modal('hide');
                                    $('#table-sucesos').DataTable().ajax.reload(); //$('.grid-sucesos').load('index.php',{action:"novedades2", operation: "sucesosRefreshGrid", id_empleado: params.id_empleado, id_contrato: $('#id_contrato').val(), id_periodo: $('#id_periodo').val()}); //para la modal (nov2)
                                    $('#example').DataTable().ajax.reload(); //$("#search").trigger("click");
                                }, 2000);
            return false; //para finalizar la ejecucion

        }



        $('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({ //ok
            backdrop: 'static',
            keyboard: false
        });


        $('#suceso-form').validate({ //ok
            errorContainer: '#myModal #myElem',
            ignore: "", //para dias1 hidden
            rules: {
                id_empleado: {required: true},
                id_evento: {required: true},
                fecha: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        //async: false,
                        data: {
                            action: "sucesos",
                            operation: "checkRango",
                            fecha_desde: function(){ return drp.startDate.format('DD/MM/YYYY');},
                            fecha_hasta: function(){ return drp.endDate.format('DD/MM/YYYY');},
                            id_empleado: function(){ return $('#id_empleado').val();},
                            id_evento: function(){ return $('#id_evento').val();},
                            id_suceso: function(){ return $('#id_suceso').val();}
                        }
                    }
                },
                id_periodo1: {required: true},
                id_periodo2: { required: false,
                               notEqual: ["#id_periodo1", "Seleccione un período de liquidación diferente al primero"]
                },
                f1: {required: true}

            },
            messages:{
                id_empleado: "Seleccione un empleado",
                id_evento: "Seleccione un suceso",
                fecha: {
                    required: "Seleccione la fecha de fin",
                    remote: "Ya existe un suceso para el empleado y evento en la fecha seleccionada"
                },
                id_periodo1: "Seleccione un período para el evento",
                f1: "Seleccione un rango de fechas para el primer período"
            }

        });


        //https://stackoverflow.com/questions/4225121/jquery-validate-sum-of-multiple-input-values
        jQuery.validator.addMethod(
            "sum",
            function (value, element, params) {
                var sumOfVals = 0;
                //sumOfVals = sumOfVals + parseInt($(this).val(), 10);
                var a = $('#cantidad1').val();
                var b = $('#cantidad2').val();
                a = a || 0; //si el campo es NaN (not a number) lo convierte en 0.
                b = b || 0; //si el campo es NaN (not a number) lo convierte en 0.
                sumOfVals = parseInt(a, 10) + parseInt(b, 10);

                //alert(sumOfVals);
                if (sumOfVals == params) return true;
                return false;
            },
            jQuery.validator.format("La suma de los días imputados debe ser {0}")
        );

        $("#dias1").rules('add', {sum: function(){ return parseInt($('#dias').val());} });
        /*jQuery.validator.addClassRules({
            cfh: {
                sum: 50
            }
        });*/







        $("#myModal #id_empleado").on('changed.bs.select', function (e) {
            //Al seleccionar un grupo, completa automaticamente el campo vencimiento y lo deshabilita.
            if ($('#id_empleado option:selected').attr('id_grupo') !='') {
                $('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).prop('disabled', true).selectpicker('refresh');
            }
            else{
                $('#id_vencimiento').selectpicker('val', '').prop('disabled', false).selectpicker('refresh');
            }

        });




    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="suceso-form" id="suceso-form" method="POST" action="index.php">
                    <input type="hidden" name="id_suceso" id="id_suceso" value="<?php print $view->suceso->getIdSuceso() ?>">


                    <?php if($view->suceso->getIdParte()){ ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info fa-fw"></i> Suceso generado automáticamente desde el parte <b><?php echo $view->suceso->getIdParte(); ?></b>.
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label for="id_empleado" class="control-label">Empleado</label>
                            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                                <?php foreach ($view->empleados as $em){
                                    ?>
                                    <option value="<?php echo $em['id_empleado']; ?>"
                                        <?php echo ($view->suceso->getIdEmpleado() == $em['id_empleado'])? 'selected' : ''; ?>
                                            data-icon="fas fa-user fa-sm fa-fw"
                                        >
                                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>



                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label for="id_evento" class="control-label">Suceso</label>
                            <select class="form-control selectpicker show-tick" id="id_evento" name="id_evento" title="Seleccione un suceso" data-live-search="true" data-size="5" data-show-subtext="true">
                                <?php foreach ($view->eventos as $ev){ ?>
                                    <option value="<?php echo $ev['id_evento']; ?>" data-subtext="<?php echo $ev['tipo_liquidacion'] ;?>"
                                        <?php echo ($ev['id_evento'] == $view->suceso->getIdEvento())? 'selected' :'' ?>
                                        >
                                        <?php echo $ev['nombre'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label" for="periodo" >Período</label>
                            <select class="form-control selectpicker show-tick" id="periodo" name="periodo" data-live-search="true" data-size="5">
                                <?php foreach ($view->años as $per){
                                    ?>
                                    <option value="<?php echo $per; ?>"
                                        <?php echo ($per == $view->suceso->getPeriodo())? 'selected' :'' ?>
                                        >
                                        <?php echo $per; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                    </div>




                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label class="control-label" for="">Fechas desde / hasta</label>
                            <!--<div class="input-group input-daterange">
                                <input class="form-control cfd" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->suceso->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly>
                                <div class="input-group-addon">hasta</div>
                                <input class="form-control cfh" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->suceso->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                            </div>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php echo ($view->suceso->getFechaDesde() && $view->suceso->getFechaHasta())? $view->suceso->getFechaDesde()." - ".$view->suceso->getFechaHasta() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dias" class="control-label">Total días</label>
                            <input type="text" class="form-control cdias" name="dias" id="dias" value = "<?php print $view->suceso->getCantidad1() + $view->suceso->getCantidad2() ?>" placeholder="" disabled >
                            <input type="hidden" name="dias1" id="dias1">
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <label for="id_periodo1" class="control-label">Imputar a período de liquidación 1</label>
                            <select class="form-control selectpicker show-tick" id="id_periodo1" name="id_periodo1" data-live-search="true" data-size="5">
                                <!-- se completa dinamicamente desde javascript cuando es un insert  -->
                                <option value="">Seleccione un período</option>
                                <?php foreach ($view->periodos as $pe){
                                    ?>
                                    <option value="<?php echo $pe['id_periodo']; ?>" <?php echo ($pe['closed_date'])? 'disabled':''; ?>
                                        <?php echo ($view->suceso->getIdPeriodo1() == $pe['id_periodo'])? 'selected' : ''; ?>
                                        >
                                        <?php echo $pe['nombre'].' ('.$pe['fecha_desde'].' - '.$pe['fecha_hasta'].')'; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">

                                <label for="chk_imputar" class="control-label">&nbsp;</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="chk_imputar" name="chk_imputar">
                                        <a href="#" title="Imputar todo al primer período seleccionado">Imputar todo</a>
                                    </label>
                                </div>


                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <!--<div class="input-group input-daterange">
                                <input class="form-control cfd" type="text" name="fd1" id="fd1" value = "<?php print $view->suceso->getFd1() ?>" placeholder="DD/MM/AAAA" readonly>
                                <div class="input-group-addon">hasta</div>
                                <input class="form-control cfh" type="text" name="fh1" id="fh1" value = "<?php print $view->suceso->getFh1() ?>" placeholder="DD/MM/AAAA" readonly>
                            </div>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="f1" id="f1" value = "<?php echo ($view->suceso->getFd1() && $view->suceso->getFh1())? $view->suceso->getFd1()." - ".$view->suceso->getFh1() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-3 required">
                            <input type="text" class="form-control cdias" name="cantidad1" id="cantidad1" value = "<?php print ($view->suceso->getCantidad1())? $view->suceso->getCantidad1() : '0'  ?>" placeholder="" disabled >
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-9">
                            <label for="id_periodo2" class="control-label">Imputar a período de liquidación 2</label>
                            <select class="form-control selectpicker show-tick" id="id_periodo2" name="id_periodo2" data-live-search="true" data-size="5">
                                <!-- se completa dinamicamente desde javascript cuando es un insert  -->
                                <option value="">Seleccione un período</option>
                                <?php foreach ($view->periodos as $pe){
                                    ?>
                                    <option value="<?php echo $pe['id_periodo']; ?>" <?php echo ($pe['closed_date'])? 'disabled':''; ?>
                                        <?php echo ($view->suceso->getIdPeriodo2() == $pe['id_periodo'])? 'selected' : ''; ?>
                                        >
                                        <?php echo $pe['nombre'].' ('.$pe['fecha_desde'].' - '.$pe['fecha_hasta'].')'; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-9 required">
                            <!--<div class="input-group input-daterange">
                                <input class="form-control cfd" type="text" name="fd2" id="fd2" value = "<?php print $view->suceso->getFd2() ?>" placeholder="DD/MM/AAAA" readonly>
                                <div class="input-group-addon">hasta</div>
                                <input class="form-control cfh" type="text" name="fh2" id="fh2" value = "<?php print $view->suceso->getFh2() ?>" placeholder="DD/MM/AAAA" readonly>
                            </div>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="f2" id="f2" value = "<?php echo ($view->suceso->getFd2() && $view->suceso->getFh2())? $view->suceso->getFd2()." - ".$view->suceso->getFh2() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-3 required">
                            <input type="text" class="form-control cdias" name="cantidad2" id="cantidad2" value = "<?php print ($view->suceso->getCantidad2())? $view->suceso->getCantidad2() : '0'  ?>" placeholder="" disabled >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-9">
                            <label class="control-label" for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" rows="2"><?php print $view->suceso->getObservaciones(); ?></textarea>
                        </div>
                    </div>

                </form>


                <div class="row">
                    <div class="form-group col-md-9">
                        <div id="fileuploader">Upload</div>
                    </div>
                </div>





                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('SUC_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



