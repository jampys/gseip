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


<?php

if($view->resultado < 0 ){ ?>


    <div id="myElem" class="msg alert alert-danger">
        <?php echo $view->error_msg; exit; ?>
    </div>



<?php

}

foreach ($view->rta as $r) {
    echo $r['ot']."<br />";
}

?>










<?php
echo "Registros procesados: " . $counter;

?>
