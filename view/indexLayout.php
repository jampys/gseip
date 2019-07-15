<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>

</head>

<body>

<?php require_once('templates/header.php'); ?>


<div class="container">


    <div id="content">
        <?php include_once ($view->contentTemplate);  ?>
    </div>

</div>





<?php require_once('templates/footer.php'); ?>


</body>


</html>