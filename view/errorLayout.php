<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){

        });

    </script>




</head>
<body>


    <?php require_once('templates/header.php'); ?>


    <div class="container">

        <div id="content" class="row">

            <div class="col-md-2"></div>

            <div class="col-md-8">

                <div class="panel panel-danger">

                    <div class="panel-heading">
                        <i class="fas fa-exclamation-triangle fa-fw"></i>&nbsp; <?php echo $view->msg_header; ?>
                    </div>

                    <div class="panel-body">
                        <?php echo $view->msg_error; ?>
                    </div>
                </div>



            </div>

            <div class="col-md-2"></div>



        </div>

    </div>

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>