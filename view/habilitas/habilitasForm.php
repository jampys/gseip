<script type="text/javascript">


    $(document).ready(function(){

        $(document).on('click', '#back',function(){
            //$("#cancel").trigger("click");
            window.history.back();
        });


    });

</script>




<a id="back" class="pull-left" href="#"><i class="fas fa-arrow-left fa-fw"></i>&nbsp;Volver </a>
<br/>
<br/>




<form name ="puesto" id="puesto" method="POST" action="index.php">
    <input type="hidden" name="id_puesto" id="id_puesto" value="<?php //print $view->puesto->getIdPuesto() ?>">



    <?php

    if($view->resultado < 0 ){ ?>


        <div id="myElem" class="msg alert alert-danger">
            <?php echo $view->error_msg; exit; ?>
        </div>



    <?php

    }


    ?>

    <!--<div class="form-group required">
        <label class="control-label" for="codigo">Código</label>
        <input class="form-control" type="text" name="codigo" id="codigo" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Código">
    </div>

    <div class="form-group required">
        <label class="control-label" for="nombre">Nombre</label>
        <input class="form-control" type="text" name="nombre" id="nombre"value = "<?php //print $view->puesto->getNombre() ?>" placeholder="Nombre">
    </div>-->


    <div class="form-group">
        <label class="control-label" for="descripcion">Conversión</label>
        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="15"><?php foreach ($view->rta as $r) {echo $r['habilita'].'  '.$r['ot'].'  '.$r['cantidad'].'  '.$r['unitario'].'  '.$r['importe']. PHP_EOL; }?></textarea>
    </div>

    <?php
    echo "Registros procesados: " . $counter;

    ?>

</form>







