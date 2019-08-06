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

<table id="example2" class="table table-striped table-condensed table-hover" cellspacing="0" width="100%">

                                    <tbody>
                                    <?php foreach ($view->rta as $r):   ?>
    <tr>
        <td></td>
        <td><?php echo $r['ot'];?></td>
        <td><?php echo $r['habilita']; ?></td>

    </tr>
<?php endforeach;  ?>
</tbody>
</table>









echo "Registros procesados: " . $counter;

?>
