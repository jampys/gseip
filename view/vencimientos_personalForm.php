<script type="text/javascript">


    $(document).ready(function(){

        //alert($('#id_habilidad').val());

        //if($('#id_habilidad').val()) alert('ahh habilidad');
        //else alert('no hay');

        var objeto={};


        /*$("#fileuploader").uploadFile({
            url:"model/upload.php",
            fileName:"myfile",
            dragDrop:true,
            showDelete: true,
            showDownload:true
        });*/


        var uploadObj = $("#fileuploader").uploadFile({
            url:"upload.php",
            dragDrop: true,
            autoSubmit: false,
            fileName: "myfile",
            returnType: "json",
            showDelete: true,
            showDownload:true,

            showCancel: true,
            showAbort: true,


            //formData: {"id_habilidad": $('#id_habilidad').val(),"codigo": $('#codigo').val(), "nombre": $('#nombre').val() },
            /*dynamicFormData: function(){
                var data ={ "id_habilidad": $('#id_habilidad').val(),"codigo": $('#codigo').val(), "nombre": $('#nombre').val()};
                return data;},*/


            dynamicFormData: function(){
                var data ={ "id": ($('#id_renovacion').val())? $('#id_renovacion').val() : objeto.id };
                return data;},

            //statusBarWidth:500,
            //dragdropWidth:500,
            maxFileSize:200*1024,
            showPreview:true,
            previewHeight: "75px",
            previewWidth: "75px",
            onSuccess:function(files,data,xhr,pd){
                //files: list of files
                //data: response from server
                //xhr : jquer xhr object
                //alert(files[0])
                //alert(data);
            },
            onLoad:function(obj) {

                $.ajax({
                    cache: false,
                    url: "index.php",
                    data:{"action": "uploads", "operation": "load", "id": $('#id_renovacion').val() },
                    type:"post",
                    dataType: "json",
                    success: function(data)
                    {
                        //alert('todo ok '+data);
                        for(var i=0;i<data.length;i++)
                        {
                            obj.createProgress(data[i]["name"],data[i]["path"],data[i]["size"]);
                        }
                    },
                    error: function(e) {
                        alert('errrorrrr '+ e.responseText);

                    }



                });
            },
            deleteCallback: function (data, pd) {
                for (var i = 0; i < data.length; i++) {
                    $.post("delete.php", {op: "delete",name: data[i]},
                        function (resp,textStatus, jqXHR) {
                            //Show Message
                            alert("File Deleted");
                        });
                }
                pd.statusbar.hide(); //You choice.

            },
            downloadCallback:function(filename,pd) {
                //location.href="download.php?filename="+filename;
                var win = window.open('index.php?action=uploads&operation=download&filename='+filename, '_blank', 'height=570,width=520,scrollbars=yes,status=yes');
                //var win = window.open(url, '_blank');
                win.focus();
            }
        });



        $('#myModal').on('click', '#submit',function(){

            if ($("#habilidad").valid()){


                //uploadObj.startUpload();



                var params={};
                params.action = 'habilidades';
                params.operation = 'saveHabilidad';
                params.id_habilidad=$('#id_habilidad').val();
                params.codigo=$('#codigo').val();
                params.nombre=$('#nombre').val();
                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data;
                    //alert(objeto.id);

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        uploadObj.startUpload();
                        $("#myElem").html('Habilidad guardada con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                    }else{
                        $("#myElem").html('Error al guardar la habilidad').addClass('alert alert-danger').show();
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


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#habilidad').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 3
                },
                nombre: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos"
                },
                nombre: "Ingrese el nombre"
            }

        });



    });

</script>





<!-- Modal -->
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
                        <label class="control-label" for="id_empleado">Empleado</label>
                        <input class="form-control" type="text" name="id_empleado" id="id_empleado" value = "<?php print $view->renovacion->getIdEmpleado() ?>" placeholder="Código">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php //print $view->habilidad->getNombre() ?>" placeholder="Nombre">
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



