<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $(document).on('click', '#about', function(){ //ok
                //preparo los parametros
                params={};
                params.action = "index";
                params.operation = "about";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });


        });

    </script>



</head>

<body>

<?php require_once('templates/header.php'); ?>


<div class="container">


    <div id="content" class="row">
        <?php //include_once ($view->contentTemplate);  ?>
    </div>

</div>

<div id="popupbox"></div>



<?php require_once('templates/footer.php'); ?>


</body>


</html>