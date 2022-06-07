<html>
<head>

    <style type="text/css">
        @font-face {
            font-family: Calibri ;
            src: url(../resources/fonts/Calibri-Regular.ttf);
        }

        p{
            font-family: Calibri;
        }
    </style>

</head>
<body>

    <div style="background-color: rgb(231, 231, 231)">
        <img style="padding: 10px; width: 60px; height: 20px" src="cid:logo_2u">
    </div>

    <div id="contenido" style="padding: 10px;">

        <p>Hola<?php //echo $emp[0]['APELLIDO'] ?><?php //echo $emp[0]['NOMBRE'] ?>,</p>
        <p>Hemos recibido una solicitud para restablecer su contraseña.</p>

        <p>Codigo de verificación: <b><?php echo $code; ?></b></p>
        <p>Si no ha enviado esta solicitud puede ignorar este mensaje.</p>
        <br/>
        <p>Gracias<a href="<?php //echo $GLOBALS['ini']['application']['app_url']; ?>"><?php //echo $GLOBALS['ini']['application']['app_name']; ?></a></p>

    </div>

</body>
</html>

