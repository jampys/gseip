<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
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


        moment.locale('es');
        $('#fecha_alta, #fecha_baja').daterangepicker({
            singleDatePicker: true,
            parentEl: '#myModal',
            autoApply: true,
            autoUpdateInput: false,
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        });


        $('.image').viewer({});

        var objeto={};



        var uploadObj = $("#fileuploader").uploadFile({
            url: "index.php?action=uploadsUsuarios&operation=upload",
            dragDrop: <?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            maxFileCount: 1,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: <?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            showDownload:true,
            showCancel: true,
            showAbort: true,
            allowDuplicates: false,
            allowedTypes: "jpg, png",

            dynamicFormData: function(){
                var data ={ "id": ($('#id_user').val())? $('#id_user').val() : objeto.id };
                return data;},

            maxFileSize:2097152, //tamaño expresado en bytes
            showPreview:true,
            previewHeight: "75px",
            previewWidth: "auto",
            uploadQueueOrder:'bottom', //el orden en que se muestran los archivos subidos.
            showFileCounter: false, //muestra el nro de archivos subidos
            downloadStr: "<i class='fas fa-download'></i>",
            deleteStr: "<i class='far fa-trash-alt'></i>",
            dragDropStr: "<span>Arrastrar &amp; Soltar</span>",
            uploadStr:"<span class='glyphicon glyphicon-plus'></span> Adjuntar",
            cancelStr: "<i class='fas fa-minus-square'></i>",

            extErrorStr: "no está permitido. Solo se permiten extensiones: ",
            duplicateErrorStr: "no permitido. El archivo ya existe.",
            sizeErrorStr: "no permitido. Tamaño máximo permitido: ",
            maxFileCountErrorStr: " no permitido. Cantidad de imágenes permitidas:",

            onLoad:function(obj){ //ok
                $.ajax({
                    cache: false,
                    url: "index.php",
                    data:{"action": "uploadsUsuarios", "operation": "load", "id": $('#id_user').val() },
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
            deleteCallback: function (data, pd) { //ok
                for (var i = 0; i < data.length; i++) {
                    $.post("index.php", {action: "uploadsUsuarios", operation: "delete", name: data[i], id: $('#id_user').val()},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploadsUsuarios&operation=download&filename="+filename;
            },
            afterUploadAll:function(obj) {
                //You can get data of the plugin using obj
                closeFormSuccess();
            }
        });



        $('#myModal').on('click', '#submit',function(){ //ok
            if ($("#usuario-form").valid()){
                var params={};
                params.action = 'sec_users';
                params.operation = 'saveUsuario';
                params.id_user = $('#id_user').val();
                params.id_empleado = $('#id_empleado').val();
                params.user = $('#user').val();
                params.fecha_baja = $('#fecha_baja').val();
                params.enabled = $('#enabled').prop('checked')? 0:1;
                //params.id_area=$('#id_area').val();
                //params.id_nivel_competencia=$('#id_nivel_competencia').val();
                //alert(params.id_puesto_superior);
                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id del usuario
                    //alert(data);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        if(uploadObj.dpCounter() >= 1) { uploadObj.startUpload(); } //se realiza el upload solo si el formulario se guardo exitosamente
                        else closeFormSuccess();
                    }else{
                        $("#myElem").html('Error al guardar el usuario').addClass('alert alert-danger').show();
                    }


                }, "json");


            }
            return false;
        });


        function closeFormSuccess(){
            $("#myElem").html('Usuario guardado con exito').addClass('alert alert-success').show();
            setTimeout(function() { $("#myElem").hide();
                                    $('#myModal').modal('hide');
                                    $('#content').load('index.php',{action:"sec_users", operation:"refreshGrid"});
                                }, 2000);
            return false; //para finalizar la ejecucion
        }


        $('#myModal #cancel').on('click', function(){
        //$(document).on('click', '#cancel',function(){ //ok
            //$('#myModal').modal('hide');
        });




        $('#usuario-form').validate({ //ok
            rules: {
                user: {
                    required: true,
                    email: true
                },
                id_empleado: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "sec_users",
                            operation: "checkEmpleado",
                            id_user: function(){ return $('#id_user').val();},
                            id_empleado: function(){ return $('#id_empleado').val();}
                        }
                        /*success: function(data, textStatus, jqXHR) {
                         console.log(textStatus, jqXHR, data);
                         },
                         error: function(data, textStatus, errorThrown) {
                         console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                         }*/

                    }
                },
                fecha_baja: {validDate: true}

            },
            messages:{
                user: {
                    required: "Ingrese un correo",
                    email: "Ingrese un correo válido"
                },
                id_empleado: {
                    required: "Seleccione un empleado",
                    remote: "El empleado ya tiene usuario"
                },
                fecha_baja: {
                    validDate: "Ingrese un formato de fecha válido DD/MM/AAAA"
                }

            }

        });



    });

</script>





<!-- Modal -->
<fieldset <?php //echo ( PrivilegedUser::dhasAction('PUE_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="usuario-form" id="usuario-form" method="POST" action="index.php">
                    <input type="hidden" name="id_user" id="id_user" value="<?php print $view->usuario->getIdUser() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Empleado</label>
                        <!--<input type="text" class="form-control empleado-group" id="empleado" name="empleado" placeholder="Empleado">
                        <input type="hidden" name="id_empleado" id="id_empleado" class="empleado-group"/>-->
                        <select id="id_empleado" name="id_empleado" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" <?php echo ($view->usuario->getIdUser())? 'disabled' :'' ?>>
                            <option value="">Seleccione un empleado</option>
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                        data-content="<span style='font-weight: bold'><?php echo $em['legajo']; ?></span> <?php echo $em['apellido'].' '.$em['nombre']; ?>"
                                    <?php echo ($em['id_empleado'] == $view->usuario->getIdEmpleado())? 'selected' :'' ?>
                                    >
                                    <?php //echo $em['legajo'].' '.$em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="user">Correo</label>
                        <input class="form-control" type="text" name="user" id="user" value = "<?php print $view->usuario->getUser() ?>" placeholder="Correo electrónico">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="enabled" name="enabled" <?php echo ($view->usuario->getIdUser() && $view->usuario->getEnabled() != 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para inhabilitar el usuario temporalmente">Inhabilitar temporalmente</a>
                            </label>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha alta</label>
                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="fecha_alta" id="fecha_alta" value = "<?php print ($view->usuario->getIdUser())? $view->usuario->getFechaAlta() : date('d/m/Y'); ?>" placeholder="DD/MM/AAAA" disabled>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha baja</label>
                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php print $view->usuario->getFechaBaja() ?>" placeholder="DD/MM/AAAA">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                    </div>


                </form>


                <div id="fileuploader">Upload</div>


                <div id="myElem" class="msg" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?>  >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>


