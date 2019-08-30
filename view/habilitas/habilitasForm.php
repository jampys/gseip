<script type="text/javascript">


    $(document).ready(function(){

        $(document).on('click', '#back',function(){
            //$("#cancel").trigger("click");
            window.history.back();
        });


        $(document).on('click', '#clipboard',function(e){
            var copyText = $("#descripcion");
            /* Select the text field */
            copyText.select();
            /* Copy the text inside the text field */
            document.execCommand("copy");
            /* Alert the copied text */
            //alert("Copied the text: " + copyText.value);
        });



        $(document).on('click', '#submit',function(){
                alert('guardar en BD');
            //if ($("#empleado-form").valid()){
                var params={};
                params.action = 'habilitas';
                params.operation = 'connection';
               // params.id_empleado=$('#id_empleado').val();

                //alert(params.cambio_domicilio);

                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado viene de un SP y siempre devuelve 1 o -1 (no lanza excepcion PHP)
                    //alert(xhr.responseText);
                    if(data >=0){
                        $(".panel-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        setTimeout(function() { $("#myElem").hide();
                            //$('#popupbox').dialog('close');
                            $('#content').load('index.php',{action:"empleados", operation:"refreshGrid"});
                        }, 2000);

                    }else{
                        //alert(xhr.responseText);
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, "json");

            //}
            return false;
        });






    });

</script>




<!--<a id="back" class="pull-left" href="#"><i class="fas fa-arrow-left fa-fw"></i>&nbsp;Volver </a>
<br/>
<br/>-->




<form name ="puesto" id="puesto" method="POST" action="index.php">
    <input type="hidden" name="id_puesto" id="id_puesto" value="<?php //print $view->puesto->getIdPuesto() ?>">



    <?php

    if($view->resultado < 0 ){ ?>


        <div id="myElem" class="msg alert alert-danger">
            <?php echo $view->error_msg;
             //exit;
            ?>
        </div>


    <?php }else{ ?>


    <span><b>Centro:</b>&nbsp;<?php echo $view->datos['centro']; ?></span><br/>
    <span><b>Certificado:</b>&nbsp;<?php echo $view->datos['certificado']; ?></span><br/>
    <span><b>Registros procesados:</b>&nbsp;<?php echo $counter; ?></span>
    <br/>
    <br/>

    <div class="form-group">
        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="15"><?php foreach ($view->rta as $r) {echo $r['habilita'].'  '.$r['ot'].'  '.$r['cantidad'].'  '.$r['unitario'].'  '.$r['importe']. PHP_EOL; }?></textarea>
    </div>

        <button class="btn btn-primary" id="clipboard" name="clipboard" type="button">Copiar al portapapeles</button>
        <button class="btn btn-primary" id="submit" name="submit" type="button">Guardar BD</button>

    <?php }?>


    <button class="btn btn-default" id="back" name="back" type="button" data-dismiss="modal">Volver</button>



</form>







