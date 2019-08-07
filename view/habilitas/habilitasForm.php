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

    <?php }?>


    <button class="btn btn-default" id="back" name="back" type="button" data-dismiss="modal">Volver</button>



</form>







