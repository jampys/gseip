<script type="text/javascript">


    $(document).ready(function(){


    });

</script>





<?php

foreach ($view->rta as $r) {
    echo $r['ot']."<br />";
}


echo "Registros procesados: " . $counter;

?>
