<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
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

            maxFileSize:3145728, //3MB tamaño expresado en bytes
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
            }
        });




        $('#myModal').on('click', '#submit',function(){

            if ($("#no_conformidad_form").valid()){

                var params={};
                params.action = 'renovacionesPersonal';
                params.operation = 'saveRenovacion';
                params.id_renovacion = $('#id_renovacion').val();
                params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                params.id_grupo = $('#id_empleado option:selected').attr('id_grupo');
                params.id_vencimiento = $('#id_vencimiento').val();
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
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Vencimiento guardado con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                                $("#search").trigger("click");
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el vencimiento').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#no_conformidad_form').validate({
            rules: {
                nombre: {required: true},
                descripcion: {required: true},
                tipo: { required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                descripcion: "Ingrese la descripción del hallazgo",
                fecha: "Selecione el tipo"
            }

        });


        $("#myModal #id_empleado").on('changed.bs.select', function (e) {
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


                <form name ="no_conformidad_form" id="no_conformidad_form" method="POST" action="index.php">
                    <input type="hidden" name="id_no_conformidad" id="id_no_conformidad" value="<?php print $view->no_conformidad->getIdNoConformidad() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="referencia">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php //print $view->renovacion->getReferencia() ?>" placeholder="Nombre">
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="descripcion">Descripción del hallazgo</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del hallazgo" rows="3"><?php //print $view->puesto->getDescripcion(); ?></textarea>
                    </div>


                    <div class="form-group required">
                        <label for="tipo" class="control-label">Tipo</label>
                            <select class="form-control selectpicker show-tick" id="tipo" name="tipo" title="Seleccione el tipo" data-live-search="true" data-size="5">
                                <?php foreach ($view->tipos['enum'] as $tipos){
                                    ?>
                                    <option value="<?php echo $tipos; ?>"
                                        <?php //echo ($nac == $view->empleado->getNacionalidad() OR ($nac == $view->nacionalidades['default'] AND !$view->empleado->getIdEmpleado()) )? 'selected' :'' ?>
                                        >
                                        <?php echo $tipos; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>


                    <div class="form-group">
                        <label for="analisis_causa" class="control-label">Análisis de causa raiz</label>
                            <?php foreach($view->analisis_causa['enum'] as $val){ ?>
                                <label class="radio-inline">
                                    <input type="radio" name="analisis_causa" value="<?php echo $val ?>"
                                        <?php //echo ($val == $view->empleado->getSexo() OR ($val == $view->sexos['default'] AND !$view->empleado->getIdEmpleado()))? 'checked' :'' ?>
                                        ><?php echo $val ?>
                                </label>
                            <?php } ?>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="analisis_causa_desc">Causa raiz</label>
                        <textarea class="form-control" name="analisis_causa_desc" id="analisis_causa_desc" placeholder="Descripción de causa raiz" rows="3"><?php //print $view->puesto->getDescripcion(); ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="tipo_accion" class="control-label">Tipo de acción</label>
                        <select class="form-control selectpicker show-tick" id="tipo_accion" name="tipo_accion" title="Seleccione el tipo de acción" data-live-search="true" data-size="5">
                            <?php foreach ($view->tipo_accion['enum'] as $ta){
                                ?>
                                <option value="<?php echo $ta; ?>"
                                    <?php //echo ($nac == $view->empleado->getNacionalidad() OR ($nac == $view->nacionalidades['default'] AND !$view->empleado->getIdEmpleado()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $ta; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="descripcion">Acción inmediata</label>
                        <textarea class="form-control" name="accion" id="accion" placeholder="Acción inmediata" rows="3"><?php //print $view->puesto->getDescripcion(); ?></textarea>
                    </div>


                    <div class="form-group required">
                        <label for="id_responsable_seguimiento" class="control-label">Responsable seguimiento</label>
                        <select id="id_responsable_seguimiento" name="id_responsable_seguimiento" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable seguimiento">
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php //echo ($em['id_empleado'] == $view->objetivo->getIdResponsableSeguimiento())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>





                </form>




                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) || $view->target=='view' )? 'disabled' : '';  ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



