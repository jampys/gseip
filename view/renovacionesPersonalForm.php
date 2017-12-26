<script type="text/javascript">


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

        $('#fecha_emision').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_vencimiento').datepicker('setStartDate', minDate);
        });

        $('#fecha_vencimiento').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_emision').datepicker('setEndDate', maxDate);
        });



        $('.image').viewer({});

        var objeto={};


        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploads&operation=upload",
            dragDrop: true,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: true,
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
                                obj.createProgress(data[i]["name"],data[i]["path"],data[i]["size"]);
                            }

                        }

                        $('img').addClass('image').css('cursor', 'zoom-in');
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
                params.fecha_emision = $('#fecha_emision').val();
                params.fecha_vencimiento = $('#fecha_vencimiento').val();

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#myElem").html('Renovación guardada con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                    }else{
                        $("#myElem").html('Error al guardar la renovación').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                        $('#myModal').modal('hide');
                    }, 2000);

                });

            }
            return false;
        });



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
                fecha_emision: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaEmision",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            id_empleado: function(){ return $('#id_empleado').val();},
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
                            id_empleado: function(){ return $('#id_empleado').val();},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }

            },
            messages:{
                id_empleado: "Seleccione un empleado o grupo",
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




    });

</script>





<!-- Modal -->
<fieldset  <?php echo ($view->renovacion->getIdRnvRenovacion())? 'disabled' : '';  ?>  >
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

                    <!--<div class="form-group required">
                        <label class="control-label" for="id_empleado">Empleado</label>
                        <select id="id_empleado" name="id_empleado" class="form-control selectpicker show-tick" data-live-search="true" title="<?php echo ($view->renovacion->getIdEmpleado())? "": "Seleccione un empleado";     ?>">
                            <option value = "<?php print $view->renovacion->getIdEmpleado() ?>">
                                <?php print $view->empleado; ?>
                            </option>
                        </select>
                    </div>-->

                    <div class="form-group required">
                        <label for="id_empleado" class="control-label">Empleado/Grupo</label>
                        <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado o grupo" data-live-search="true" data-size="5">
                            <?php foreach ($view->empleadosGrupos as $eg){
                                ?>
                                <option
                                    value="<?php echo ($eg['id_empleado'])? $eg['id_empleado'] : $eg['id_grupo']; ?>"
                                    id_empleado="<?php echo $eg['id_empleado']; ?>"
                                    id_grupo="<?php echo $eg['id_grupo']; ?>"
                                    <?php
                                            if($eg['id_empleado'] && $view->renovacion->getIdEmpleado() == $eg['id_empleado']) echo 'selected';
                                            elseif($eg['id_grupo'] && $view->renovacion->getIdGrupo() == $eg['id_grupo']) echo 'selected';
                                    ?>
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
                            <input class="form-control" type="text" name="fecha_emision" id="fecha_emision" value = "<?php print $view->renovacion->getFechaEmision() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">hasta</div>
                            <input class="form-control" type="text" name="fecha_vencimiento" id="fecha_vencimiento" value = "<?php print $view->renovacion->getFechaVencimiento() ?>" placeholder="DD/MM/AAAA">
                        </div>
                    </div>


                </form>


                <div id="fileuploader">Upload</div>





                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



