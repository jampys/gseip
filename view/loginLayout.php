<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){


            $(document).on('click', '#ingresar',function(){
                //if ($("#client").valid()){
                    //alert('ingresar');
                    var params={};
                    params.action='login';
                    params.operation='login';
                    params.usuario=$('#usuario').val();
                    params.contraseña=$('#contraseña').val();

                    $.post('index.php',params,function(data, status, xhr){
                        //alert(data);
                        if(data >=1){
                            //Accede al sistema
                            //$("#myElem").html('Acceso con exito').addClass('alert alert-success').show();
                            //$('#content').load('index.php',{action:"index"});
                            /*params={};
                            params.action="index";
                            $.post('controller/indexLayout.php',params,function(data, status, xhr){
                                alert(data);
                            });*/
                            window.location.href = "index.php?action=index";
                        }else if (data == 0){
                            $("#myElem").html('Usuario inhabilitado').addClass('alert alert-danger').show();
                            setTimeout(function() { $("#myElem").hide();
                                                  }, 2000);
                        }else if (data == -1){
                            $("#myElem").html('Usuario o contraseña invalidos').addClass('alert alert-danger').show();
                            setTimeout(function() { $("#myElem").hide();
                            }, 2000);
                        }


                    });

                //}
                return false;
            });






        });

    </script>




</head>
<body style="padding-top: 0px">


<div class="container">


    <div class="row" style="margin-top: 50px">

        <div id="content" class="col-md-4 col-md-offset-4">
            <?php include_once ($view->contentTemplate); // incluyo el template que corresponda ?>
        </div>

    </div>

</div>



<?php require_once('templates/footer.php'); ?>

</div>



<?php require_once('templates/footer.php'); ?>


</body>


</html>