<script type="text/javascript">


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
            url: "index.php?action=uploadsPostulantes&operation=upload",
            dragDrop: <?php echo ( PrivilegedUser::dhasAction('PTE_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: <?php echo ( PrivilegedUser::dhasAction('PTE_UPDATE', array(1)) && $view->target!='view' )? 'true' : 'false' ?>,
            showDownload:true,
            showCancel: true,
            showAbort: true,
            allowDuplicates: false,
            allowedTypes: "jpg, png, pdf, txt, doc, docx",

            dynamicFormData: function(){
                var data ={ "id": ($('#id_postulante').val())? $('#id_postulante').val() : objeto.id };
                return data;},

            maxFileSize:5242880, //5MB de tamaño expresado en bytes
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
                    data:{"action": "uploadsPostulantes", "operation": "load", "id": $('#id_postulante').val() },
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
                    $.post("index.php", {action: "uploadsPostulantes", operation: "delete", name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            //alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                location.href="index.php?action=uploadsPostulantes&operation=download&filename="+filename;
            }
        });




        /*$('#myModal').on('click', '#submit',function(){

            if ($("#postulante-form").valid()){

                var params={};
                params.action = 'postulantes';
                params.operation = 'savePostulante';
                params.id_postulante = $('#id_postulante').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                params.apellido = $('#apellido').val();
                params.nombre = $('#nombre').val();
                params.dni = $('#dni').val();
                params.lista_negra = $('#lista_negra').prop('checked')? 1:0;
                params.telefono = $('#telefono').val();
                params.formacion = $('#formacion').val();
                params.id_especialidad = $('#id_especialidad').val();
                params.id_localidad = $('#id_localidad').val();
                params.comentarios = $('#comentarios').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Postulante guardado con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el postulante').addClass('alert alert-danger').show();
                });

            }
            return false;
        });*/



        /*$('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });*/


        /*$('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });*/


        $('#postulante-form').validate({ //ok
            rules: {
                apellido: {required: function(){return $('#etapas_right_side').data('nuevo') == 1;}},
                nombre: {required: function(){return $('#etapas_right_side').data('nuevo') == 1;}},
                dni: {
                    //required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "postulantes",
                            operation: "checkDni",
                            dni: function(){ return $('#dni').val();},
                            id_postulante: function(){ return $('#id_postulante').val();}
                        }
                    }
                },
                comentarios: {maxlength: 500}

            },
            messages:{
                apellido: "Ingrese el apellido",
                nombre: "Ingrese el nombre",
                dni: {
                    //required: "Ingrese el DNI",
                    remote: "El postulante ya se encuentra registrado"
                },
                comentarios: "Máximo 500 caracteres"

            }

        });



    });

</script>



<form name ="postulante-form" id="postulante-form" method="POST" action="index.php">

                    <input type="hidden" name="id_postulante" id="id_postulante" value="<?php //print $view->postulante->getIdPostulante() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="apellido">Apellido</label>
                        <input class="form-control" type="text" name="apellido" id="apellido" value = "<?php //print $view->postulante->getApellido() ?>" placeholder="Apellido">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php //print $view->postulante->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="dni">DNI</label>
                        <input class="form-control" type="text" name="dni" id="dni" value = "<?php //print $view->postulante->getDni() ?>" placeholder="DNI">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="telefono">Teléfono</label>
                        <input class="form-control" type="text" name="telefono" id="telefono" value = "<?php //print $view->postulante->getTelefono() ?>" placeholder="Teléfono">
                    </div>

                    <div class="form-group">
                        <label for="id_localidad" class="control-label">Ubicación</label>
                        <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" title="Seleccione la localidad" data-live-search="true" data-size="5">
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>"
                                    <?php echo ($loc['id_localidad'] == $view->postulante->getIdLocalidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="formacion" class="control-label">Formación</label>
                        <select class="form-control selectpicker show-tick" id="formacion" name="formacion" title="Seleccione la formación"  data-live-search="true" data-size="5">
                            <?php foreach ($view->formaciones['enum'] as $fo){
                                ?>
                                <option value="<?php echo $fo; ?>"
                                    <?php echo ($fo == $view->postulante->getFormacion() OR ($fo == $view->formaciones['default'] AND !$view->formacion->getFormacion()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $fo; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_especialidad" class="control-label">Especialidad</label>
                        <select class="form-control selectpicker show-tick" id="id_especialidad" name="id_especialidad" title="Seleccione la especialidad" data-live-search="true" data-size="5">
                            <?php foreach ($view->especialidades as $es){
                                ?>
                                <option value="<?php echo $es['id_especialidad']; ?>"
                                    <?php echo ($es['id_especialidad'] == $view->postulante->getIdEspecialidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $es['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <!--<div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lista_negra" name="lista_negra" <?php //echo (!$view->postulante->getListaNegra())? '' :'checked' ?> ><a href="#" title="Seleccione para incluir al postulante en la lista negra">Agregar a lista negra</a>
                            </label>
                        </div>
                    </div>-->


                    <div class="form-group">
                        <label class="control-label" for="comentarios">Comentarios</label>
                        <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php //print $view->postulante->getComentarios(); ?></textarea>
                    </div>


</form>

                <div id="fileuploader">Upload</div>



                <!--<div id="myElem" class="msg" style="display:none"></div>-->







