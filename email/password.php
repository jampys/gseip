<?php

/* Este archivo es un template con el formato de e-mail para el blanqueo de password

    Tener cuidado que los email client no soportan CSS => hay que escribirlos inline
    http://stackoverflow.com/questions/12751147/how-to-send-an-email-that-has-html-and-css-in-php   */

?>

<html>
<head>

</head>
<body>

    <div style="background-color: rgb(231, 231, 231)">
        <img style="padding: 10px; width: 60px; height: 20px" src="cid:logo_2u">
    </div>

    <div id="contenido" style="padding: 10px; font-family: 'Roboto', sans-serif">

        <p>Hola <?php //echo $emp[0]['APELLIDO'] ?> <?php //echo $emp[0]['NOMBRE'] ?>,</p>
        <p>Hemos recibido una solicitud para restablecer su contraseña.</p>

        <p>Codigo de verificación: <b><?php echo $code; ?></b>></p>
        <p>Si no ha enviado esta solicitud puede ignorar este mensaje.</p>
        <br/>
        <p>Gracias<a href="<?php //echo $GLOBALS['ini']['app_url']; ?>"><?php //echo $GLOBALS['ini']['app_name']; ?></a></p>

    </div>

</body>
</html>

