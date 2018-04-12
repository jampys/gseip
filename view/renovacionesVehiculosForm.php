﻿<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        /*$('#fecha_emision').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_vencimiento').datepicker('setStartDate', minDate);
        });

        $('#fecha_vencimiento').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_emision').datepicker('setEndDate', maxDate);
        });*/



        $('.image').viewer({});

        var objeto={};


        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploadsVehiculos&operation=upload",
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

            maxFileSize:2097152, //tamaño expresado en bytes
            showPreview:true,
            previewHeight: "75px",
            previewWidth: "auto",
            uploadQueueOrder:'bottom', //el orden en que se muestran los archivos subidos.
            showFileCounter: false, //muestra el nro de archivos subidos
            downloadStr: "<span class='glyphicon glyphicon-download'></span>",
            deleteStr: "<span class='glyphicon glyphicon-trash'></span>",
            dragDropStr: "<span><b>Arrastrar &amp; Soltar</b></span>",
            uploadStr:"<span class='glyphicon glyphicon-plus'></span> Subir",
            cancelStr: "<span class='glyphicon glyphicon-remove-circle'></span>",

            extErrorStr: "no está permitido. Solo se permiten extensiones: ",
            duplicateErrorStr: "no permitido. El archivo ya existe.",
            sizeErrorStr: "no permitido. Tamaño máximo permitido: ",

            onLoad:function(obj){
                $.ajax({
                    cache: false,
                    url: "index.php",
                    data:{"action": "uploadsVehiculos", "operation": "load", "id": $('#id_renovacion').val() },
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
                                obj.createProgress(data[i]["name"],data[i]["path"],data[i]["size"]);
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
                    $.post("index.php", {action: "uploadsVehiculos", operation: "delete", name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploadsVehiculos&operation=download&filename="+filename;
            }
        });




        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#renovacion_vehicular").valid()){

                var params={};
                params.action = 'renovacionesVehiculos';
                params.operation = 'saveRenovacion';
                params.id_renovacion = $('#id_renovacion').val();
                params.id_vehiculo = $('#id_vehiculo option:selected').attr('id_vehiculo');
                params.id_grupo = $('#id_vehiculo option:selected').attr('id_grupo');
                params.id_vencimiento = $('#id_vencimiento').val();
                params.fecha_emision = $('#fecha_emision').val();
                params.fecha_vencimiento = $('#fecha_vencimiento').val();

                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#myElem").html('Renovación guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            $('#myModal').modal('hide');
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la renovación').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#myModal #cancel').on('click', function(){ //ok
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({ //ok
            backdrop: 'static',
            keyboard: false
        });


        $('#renovacion_vehicular').validate({ //ok
            rules: {
                id_vehiculo: {required: true},
                id_vencimiento: {required: true},
                fecha_emision: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesVehiculos",
                            operation: "checkFechaEmision",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            id_vehiculo: function(){ return $('#id_vehiculo option:selected').attr('id_vehiculo');},
                            id_grupo: function(){ return $('#id_vehiculo option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                },
                fecha_vencimiento: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesVehiculos",
                            operation: "checkFechaVencimiento",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            fecha_vencimiento: function(){ return $('#fecha_vencimiento').val();},
                            id_vehiculo: function(){ return $('#id_vehiculo option:selected').attr('id_vehiculo');},
                            id_grupo: function(){ return $('#id_vehiculo option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }

            },
            messages:{
                id_vehiculo: "Seleccione un vehículo o grupo",
                id_vencimiento: "Seleccione un vencimiento",
                fecha_emision: {
                    required: "Ingrese la fecha de emisión",
                    remote: "La fecha de emisión debe ser mayor"
                },
                fecha_vencimiento: {
                    required: "Ingrese la fecha de vencimiento",
                    remote: "La fecha de vencimiento debe ser mayor"
                }
            }

        });


        $("#myModal #id_vehiculo").on('changed.bs.select', function (e) { //ok
            //Al seleccionar un grupo, completa automaticamente el campo vencimiento y lo deshabilita.
            if ($('#id_vehiculo option:selected').attr('id_grupo') !='') {
                $('#id_vencimiento').selectpicker('val', $('#id_vehiculo option:selected').attr('id_vencimiento')).prop('disabled', true).selectpicker('refresh');
            }
            else{
                $('#id_vencimiento').selectpicker('val', '').prop('disabled', false).selectpicker('refresh');
            }

        });




    });

</script>



<!-- Modal -->
<fieldset  <?php echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RVE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="renovacion_vehicular" id="renovacion_vehicular" method="POST" action="index.php">
                    <input type="hidden" name="id_renovacion" id="id_renovacion" value="<?php print $view->renovacion->getIdRenovacion() ?>">


                    <div class="form-group required">
                        <label for="id_vehiculo" class="control-label">Vehículo / Grupo</label>
                        <select class="form-control selectpicker show-tick" id="id_vehiculo" name="id_vehiculo" title="Seleccione un vehículo o grupo" data-live-search="true" data-size="5">
                            <?php foreach ($view->vehiculosGrupos as $eg){
                                ?>
                                <option
                                    value="<?php echo ($eg['id_vehiculo'])? $eg['id_vehiculo'] : $eg['id_grupo']; ?>"
                                    id_vehiculo="<?php echo $eg['id_vehiculo']; ?>"
                                    id_grupo="<?php echo $eg['id_grupo']; ?>"
                                    id_vencimiento="<?php echo $eg['id_vencimiento']; ?>"
                                    <?php
                                            if($eg['id_vehiculo'] && $view->renovacion->getIdVehiculo() == $eg['id_vehiculo']) echo 'selected';
                                            elseif($eg['id_grupo'] && $view->renovacion->getIdGrupo() == $eg['id_grupo']) echo 'selected';
                                    ?>
                                    data-icon="<?php echo ($eg['id_vehiculo'])? "fas fa-user fa-sm" : "fas fa-users fa-sm"; ?>"
                                    >
                                    &nbsp;
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


                    <div class="form-group required">
                        <label class="control-label" for="">Emisión / Vencimiento</label>

                        <?php if(!$view->renovacion->getIdRnvRenovacion()){ ?>
                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <?php if($view->renovacion->getIdRenovacion()){ //Es un edit ?>
                                <span class="glyphicon glyphicon-tags" ></span>&nbsp La fecha de emsión debe ser mayor a la de la renovación anterior.
                                <br/><span class="glyphicon glyphicon-tags" ></span>&nbsp La fecha de vencimiento debe ser mayor a la de la renovación anterior.
                            <?php }else { //Es una renovacion ?>
                                <span class="glyphicon glyphicon-tags" ></span>&nbsp La fecha de emsión debe ser mayor a la de la renovación vigente.
                                <br/><span class="glyphicon glyphicon-tags" ></span>&nbsp La fecha de vencimiento debe ser mayor a la de la renovación vigente.
                            <?php } ?>

                        </div>
                        <?php } ?>

                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_emision" id="fecha_emision" value = "<?php print $view->renovacion->getFechaEmision() ?>" placeholder="DD/MM/AAAA" readonly>
                            <div class="input-group-addon">hasta</div>
                            <input class="form-control" type="text" name="fecha_vencimiento" id="fecha_vencimiento" value = "<?php print $view->renovacion->getFechaVencimiento() ?>" placeholder="DD/MM/AAAA" readonly>
                        </div>
                    </div>


                </form>


                <div id="fileuploader">Upload</div>





                <div id="myElem" class="msg" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>


