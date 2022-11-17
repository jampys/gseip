﻿<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        moment.locale('es');
        $('#fecha').daterangepicker({
            parentEl: '#myModal #renovacion_personal',
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            linkedCalendars: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
            picker.element.valid();
        });
        var drp = $('#fecha').data('daterangepicker');


        $('.image').viewer({});

        var objeto={};


        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploads&operation=upload",
            dragDrop: <?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: <?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            showDownload:true,
            showCancel: true,
            showAbort: true,
            allowDuplicates: false,
            allowedTypes: "jpg, png, pdf, txt, doc, docx",

            dynamicFormData: function(){
                var data ={ "id": ($('#id_renovacion').val())? $('#id_renovacion').val() : objeto.id };
                return data;},

            maxFileSize:5242880, //5MB tamaño expresado en bytes
            showPreview:true,
            previewHeight: "75px",
            previewWidth: "auto",
            uploadQueueOrder:'bottom', //el orden en que se muestran los archivos subidos.
            showFileCounter: false, //muestra el nro de archivos subidos
            downloadStr: "<i class='fas fa-download'></i>",
            deleteStr: "<i class='far fa-trash-alt'></i>",
            dragDropStr: "<span>Arrastrar &amp; Soltar</span>",
            uploadStr:"<i class='fas fa-plus'></i> Adjuntar",
            cancelStr: "<i class='fas fa-minus-square'></i>",

            extErrorStr: "no está permitido. Solo se permiten extensiones: ",
            duplicateErrorStr: "no permitido. El archivo ya existe.",
            sizeErrorStr: "no permitido. Tamaño máximo permitido: ",

            onLoad:function(obj){
                $.ajax({
                    cache: false,
                    url: "index.php",
                    data:{"action": "uploads", "operation": "load", "id": $('#id_renovacion').val() },
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
                    $.post("index.php", {action: "uploads", operation: "delete", name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploads&operation=download&filename="+filename;
            },
            afterUploadAll:function(obj) {
                //You can get data of the plugin using obj
                closeFormSuccess();
            }
        });




        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#renovacion_personal").valid()){

                var params={};
                params.action = 'renovacionesPersonal';
                params.operation = 'saveRenovacion';
                params.id_renovacion = $('#id_renovacion').val();
                params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                params.id_grupo = $('#id_empleado option:selected').attr('id_grupo');
                params.id_vencimiento = $('#id_vencimiento').val();
                //params.fecha_emision = $('#fecha_emision').val();
                //params.fecha_vencimiento = $('#fecha_vencimiento').val();
                params.fecha_emision = drp.startDate.format('DD/MM/YYYY');
                params.fecha_vencimiento = drp.endDate.format('DD/MM/YYYY');
                params.disabled = $('#disabled').prop('checked')? 1:0;
                params.referencia = $('#referencia').val();

                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado (solo para el caso del insert) viene de un SP y siempre devuelve 1 o -1 (no lanza excepcion PHP)
                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        if(uploadObj.dpCounter() >= 1) { uploadObj.startUpload(); } //se realiza el upload solo si el formulario se guardo exitosamente
                        else closeFormSuccess();
                    }else{
                        $("#myElem").html('No es posible guardar el vencimiento').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });

        function closeFormSuccess(){
            $("#myElem").html('Vencimiento guardado con exito').addClass('alert alert-success').show();
            setTimeout(function() { $("#myElem").hide();
                                    $('#myModal').modal('hide');
                                    $('#example').DataTable().ajax.reload(null, false); //$("#search").trigger("click");
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


        $('#renovacion_personal').validate({ //ok
            rules: {
                id_empleado: {required: true},
                id_vencimiento: {required: true},
                fecha: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        //async: false,
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkRangoFechas",
                            fecha_emision: function(){ return drp.startDate.format('DD/MM/YYYY');},
                            fecha_vencimiento: function(){ return drp.endDate.format('DD/MM/YYYY');},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }

            },
            messages:{
                id_empleado: "Seleccione un empleado o grupo",
                id_vencimiento: "Seleccione un vencimiento",
                fecha: {
                    required: "Selecione el rango de fechas",
                    remote: "Seleccione un rango de fechas válido"
                }
            }

        });


        $("#myModal #id_empleado").on('changed.bs.select', function (e) { //ok
            //Al seleccionar un grupo, completa automaticamente el campo vencimiento y lo deshabilita.
            if ($('#id_empleado option:selected').attr('id_grupo') !='') {
                //$('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).prop('disabled', true).selectpicker('refresh');
                $('#id_vencimiento').selectpicker('val', $('#id_empleado option:selected').attr('id_vencimiento')).selectpicker('refresh');
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


                <form name ="renovacion_personal" id="renovacion_personal" method="POST" action="index.php">
                    <input type="hidden" name="id_renovacion" id="id_renovacion" value="<?php print $view->renovacion->getIdRenovacion() ?>">


                    <div class="form-group required">
                        <label for="id_empleado" class="control-label">Empleado / Grupo</label>
                        <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado o grupo" data-live-search="true" data-size="5">
                            <?php foreach ($view->empleadosGrupos as $eg){
                                ?>
                                <option
                                    value="<?php echo ($eg['id_empleado'])? $eg['id_empleado'] : $eg['id_grupo']; ?>"
                                    id_empleado="<?php echo $eg['id_empleado']; ?>"
                                    id_grupo="<?php echo $eg['id_grupo']; ?>"
                                    id_vencimiento="<?php echo $eg['id_vencimiento']; ?>"
                                    <?php
                                            if($eg['id_empleado'] && $view->renovacion->getIdEmpleado() == $eg['id_empleado']) echo 'selected';
                                            elseif($eg['id_grupo'] && $view->renovacion->getIdGrupo() == $eg['id_grupo']) echo 'selected';
                                    ?>
                                    data-icon="<?php echo ($eg['id_empleado'])? "fad fa-male fa-sm fa-fw" : "fad fa-users fa-sm fa-fw"; ?>"
                                    >
                                    <?php echo $eg['descripcion'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label for="id_vencimiento" class="control-label">Vencimiento</label>
                            <select class="form-control selectpicker show-tick" id="id_vencimiento" name="id_vencimiento" title="Seleccione el vencimiento" data-live-search="true" data-size="5">
                                <?php foreach ($view->vencimientos as $vto){
                                    ?>
                                    <option value="<?php echo $vto['id_vencimiento']; ?>"
                                        <?php echo ($vto['id_vencimiento'] == $view->renovacion->getIdVencimiento())? 'selected' :'' ?>
                                        >
                                        <?php echo $vto['nombre'] ;?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="referencia">Referencia</label>
                        <input class="form-control" type="text" name="referencia" id="referencia" value = "<?php print $view->renovacion->getReferencia() ?>" placeholder="Nro. de referencia">
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="">Emisión / Vencimiento</label>

                        <?php if(!$view->renovacion->getIdRnvRenovacion()){ ?>
                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <?php if($view->renovacion->getIdRenovacion()){ //Es un edit ?>
                                <i class="fas fa-tags"></i>&nbsp La fecha de emsión debe ser mayor a la de la renovación anterior.
                                <br/><i class="fas fa-tags"></i>&nbsp La fecha de vencimiento debe ser mayor a la de la renovación anterior.
                            <?php }else { //Es una renovacion ?>
                                <i class="fas fa-tags"></i>&nbsp La fecha de emsión debe ser mayor a la de la renovación vigente.
                                <br/><i class="fas fa-tags"></i>&nbsp La fecha de vencimiento debe ser mayor a la de la renovación vigente.
                            <?php } ?>

                        </div>
                        <?php } ?>

                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="fecha" id="fecha" value = "<?php echo ($view->renovacion->getFechaEmision() && $view->renovacion->getFechaVencimiento())? $view->renovacion->getFechaEmision()." - ".$view->renovacion->getFechaVencimiento() : "";  ?>" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                            <i class="fad fa-calendar-alt"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="disabled" name="disabled" <?php echo (!$view->renovacion->getDisabled())? '' :'checked' ?> <?php echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para desactivar el alerta del vencimiento">Desactivar</a>
                            </label>
                        </div>
                    </div>


                </form>


                <div id="fileuploader">Upload</div>





                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) || $view->target=='view' )? 'disabled' : '';  ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>


