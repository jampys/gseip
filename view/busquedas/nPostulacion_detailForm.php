<style>

    #culo:after, #culo2:after {  /* icono de un nodo cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f054";
        /*color: #5fba7d;*/
    }

    #culo.highlight:after, #culo2.highlight:after {  /* icono de un nodo abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f078";
        /*color: #5fba7d;*/
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){



        $('#etapas_right_side').data('nuevo', 0);

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
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
            sizeErrorStr: "no permitido. Tamaño máximo permitido: "

            /*onLoad:function(obj){
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
             }*/
        });



        /*$('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });*/


        //cancel de formulario de postulacion
        $('#myModal #cancel').on('click', function(){
            $('#chalampa').hide();
            return false;
        });


        $('#postulacion-form').validate({
            rules: {
                //id_postulante: {required: function(){return $('#etapas_right_side').data('nuevo') == 0;}},
                id_postulante: {
                    required: function(){return $('#etapas_right_side').data('nuevo') == 0;},
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "postulaciones2",
                            operation: "checkPostulacion",
                            id_postulante: function(){ return $('#id_postulante').val();},
                            id_busqueda: function(){ return $('#myModal #id_busqueda').val();},
                            id_postulacion: function(){ return $('#id_postulacion').val();}
                        }
                    }
                },
                origen_cv: {required: true}

            },
            messages:{
                //id_postulante: "Seleccione el postulante",
                id_postulante: {
                    required: "Seleccione un postulante",
                    remote: "El postulante ya se encuentra en la búsqueda"
                },
                origen_cv: "Seleccione el origen del CV"
            }

        });




        $('#chalampa').on('click', '#culo', function(e) { //ok
            var selected = $(this).hasClass("highlight");
            if(!selected){
                //alert('abrir');
                $(this).addClass("highlight");
                //params={};
                //params.action = "postulaciones2";
                //params.operation = "newPostulante";
                //$('#box1 .panel-body').load('index.php', params,function(){
                    //$('#myModal').modal();
                    //$('#etapas_left_side #add').attr('id_busqueda', id);
                    $('#box1').show();
                    $('#id_postulante').val('').selectpicker('refresh');
                    $('#id_postulante_form_group').hide();
                    $('#etapas_right_side').data('nuevo', 1);
                    //alert(nuevo);
                //})
            }else{
                //alert('cerrar');
                $(this).removeClass("highlight");
                $('#box1').hide();
                $('#id_postulante_form_group').show();
                $('#etapas_right_side').data('nuevo', 0);
            }
            return false;

        });



        //Guardar postulacion luego de ingresar nueva o editar
        //$('#etapas_right_side').on('click', '#submit',function(){ //ok
        $('#submit').on('click',function(){ //ok
            //alert('guardar postulacion');
            //alert($('#myModal #id_busquedax').val());
            //throw new Error();

            //$('#postulacion-form').validate().resetForm(); //limpiar error input validate
            $('#postulacion-form').find('input').closest('.form-group').removeClass('has-error');
            $('#postulante-form').find('input').closest('.form-group').removeClass('has-error');
            $('#postulacion-form .tooltip').remove(); //limpiar error tooltip validate
            $('#postulante-form .tooltip').remove(); //limpiar error tooltip validate

            if ($("#postulacion-form").valid() && $("#postulante-form").valid() ){

                //alert('paso la validacion');
                //throw new Error();
                var params={};
                params.action = 'postulaciones2';
                params.operation = 'savePostulacion';
                params.id_busqueda = $('#myModal #id_busquedax').val();
                params.id_postulante = $('#id_postulante').val();
                params.id_postulacion = $('#id_postulacion').val();
                params.origen_cv = $('#origen_cv').val();
                params.expectativas = $('#expectativas').val();
                params.propuesta_economica = $('#propuesta_economica').val();

                params.apellido = $('#apellido').val();
                params.nombre = $('#nombre').val();
                //alert(params.id_busqueda);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    objeto.id = data['id_postulante']; //data trae el id del postulante

                    if(data['msg'] >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#chalampa #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Postulación guardada con exito').addClass('alert alert-success').show();
                        //$('#etapas_left_side .grid').load('index.php',{action:"postulaciones2", id_busqueda:params.id_busqueda, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#chalampa').hide();
                                                $('#table-postulantes').DataTable().ajax.reload();
                                                $('#table-etapas').DataTable().ajax.reload();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la postulación').addClass('alert alert-danger').show();
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    alert('Entro a fail '+jqXHR.responseText);
                });

            }
            return false;
        });




    });

</script>


<div id="chalampa">

    <a href="#" id="culo" title="nuevo postulante">Nuevo postulante&nbsp;</a>

    <div class="panel panel-default" id="box1" style="display: none">
        <div class="panel-body" style="background-color: #e5e5e5">
            <?php include_once('view/busquedas/nPostulante_detailForm.php');?>
        </div>
    </div>


<form name ="postulacion-form" id="postulacion-form" method="POST" action="index.php">
    <fieldset>

        <!--<div class="alert alert-info">
        <strong><?php //echo $view->label ?></strong>
    </div>-->

    <input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php print $view->postulacion->getIdPostulacion() ?>">
    <!--<input type="hidden" name="id_busqueda" id="id_busqueda" value="<?php //print $view->postulacion->getIdBusqueda() ?>">-->




        <div class="form-group" id="id_postulante_form_group">
            <!--<label for="id_postulante" class="control-label">Postulante</label>-->
            <select class="form-control selectpicker show-tick" id="id_postulante" name="id_postulante" data-live-search="true" data-size="5">
                <option value="">Seleccione un postulante</option>
                <?php foreach ($view->postulantes as $po){
                    ?>
                    <option value="<?php echo $po['id_postulante']; ?>"
                        <?php echo ($po['id_postulante'] == $view->postulacion->getIdPostulante())? 'selected' :'' ?>
                        >
                        <?php echo $po['apellido']." ".$po['nombre']." ".$po['dni'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="origen_cv" class="control-label">Origen del CV</label>
            <select class="form-control selectpicker show-tick" id="origen_cv" name="origen_cv" title="Seleccione el origen del CV" data-live-search="true" data-size="5">
                <?php foreach ($view->origenes_cv['enum'] as $cv){
                    ?>
                    <option value="<?php echo $cv; ?>"
                        <?php echo ($cv == $view->postulacion->getOrigenCv() OR ($cv == $view->origenes_cv['default'] AND !$view->postulacion->getIdPostulacion()) )? 'selected' :'' ?>
                        >
                        <?php echo $cv; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label" for="expectativas">Expectativas ($)</label>
                <input class="form-control" type="text" name="expectativas" id="expectativas" value = "<?php print $view->postulacion->getExpectativas() ?>" placeholder="Expectativas">
            </div>
            <div class="form-group col-md-6">
                <label class="control-label" for="expectativas">Propuesta económica ($)</label>
                <input class="form-control" type="text" name="propuesta_economica" id="propuesta_economica" value = "<?php print $view->postulacion->getPropuestaEconomica() ?>" placeholder="Propuesta económica">
            </div>
        </div>

    </fieldset>
</form>


    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php echo ( PrivilegedUser::dhasAction('PTN_UPDATE', array(1)) && $view->target!='view')? '' : 'disabled' ?>   >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>



</div>












