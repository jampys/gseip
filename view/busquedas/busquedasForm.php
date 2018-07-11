﻿<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        $('.image').viewer({});

        var objeto={};


        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploadsBusquedas&operation=upload",
            dragDrop: <?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: <?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            showDownload:true,
            showCancel: true,
            showAbort: true,
            allowDuplicates: false,
            allowedTypes: "jpg, png, pdf, txt, doc, docx",

            dynamicFormData: function(){
                var data ={ "id": ($('#id_busqueda').val())? $('#id_busqueda').val() : objeto.id };
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
                    data:{"action": "uploadsBusquedas", "operation": "load", "id": $('#id_busqueda').val() },
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
                    $.post("index.php", {action: "uploadsBusquedas", operation: "delete", name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploadsBusquedas&operation=download&filename="+filename;
            }
        });




        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#busqueda-form").valid()){

                var params={};
                params.action = 'busquedas';
                params.operation = 'saveBusqueda';
                params.id_busqueda = $('#id_busqueda').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.nombre = $('#nombre').val();
                params.fecha_apertura = $('#fecha_apertura').val();
                params.fecha_cierre = $('#fecha_cierre').val();
                params.id_puesto = $('#id_puesto').val();
                params.id_localidad = $('#id_localidad').val();
                params.id_contrato = $('#id_contrato').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Búsqueda guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la búsqueda').addClass('alert alert-danger').show();
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


        $('#busqueda-form').validate({ //ok
            rules: {
                nombre: {required: true},
                fecha_apertura: {required: true}
                /*fecha_emision: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaEmision",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
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
                            action: "renovacionesPersonal",
                            operation: "checkFechaVencimiento",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            fecha_vencimiento: function(){ return $('#fecha_vencimiento').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }*/

            },
            messages:{
                nombre: "Ingrese el nombre",
                fecha_apertura: "Seleccione la fecha de apertura"
                /*fecha_emision: {
                    required: "Ingrese la fecha de emisión",
                    remote: "La fecha de emisión debe ser mayor"
                },
                fecha_vencimiento: {
                    required: "Ingrese la fecha de vencimiento",
                    remote: "La fecha de vencimiento debe ser mayor"
                }*/
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


                <form name ="busqueda-form" id="busqueda-form" method="POST" action="index.php">
                    <input type="hidden" name="id_busqueda" id="id_busqueda" value="<?php print $view->busqueda->getIdBusqueda() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->busqueda->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="fecha_apertura">Fecha apertura</label>
                        <div class="input-group date">
                            <input class="form-control" type="text" name="fecha_apertura" id="fecha_apertura" value = "<?php print $view->busqueda->getFechaApertura() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="fecha_cierre">Fecha cierre</label>
                        <div class="input-group date">
                            <input class="form-control" type="text" name="fecha_cierre" id="fecha_cierre" value = "<?php print $view->busqueda->getFechaCierre() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="id_vencimiento" class="control-label">Puesto</label>
                            <select class="form-control selectpicker show-tick" id="id_puesto" name="id_puesto" data-live-search="true" data-size="5">
                                <option value="">Seleccione un puesto</option>
                                <?php foreach ($view->puestos as $pu){
                                    ?>
                                    <option value="<?php echo $pu['id_puesto']; ?>"
                                        <?php echo ($pu['id_puesto'] == $view->busqueda->getIdPuesto())? 'selected' :'' ?>
                                        >
                                        <?php echo $pu['nombre'] ;?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="id_localidad" class="control-label">Área</label>
                        <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" data-live-search="true" data-size="5">
                            <option value="">Seleccione un área</option>
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>"
                                    <?php echo ($loc['id_localidad'] == $view->busqueda->getIdLocalidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $co){
                                ?>
                                <option value="<?php echo $co['id_contrato']; ?>"
                                    <?php echo ($co['id_contrato'] == $view->busqueda->getIdContrato())? 'selected' :'' ?>
                                    >
                                    <?php echo $co['nombre'].' '.$co['nro_contrato'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
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


