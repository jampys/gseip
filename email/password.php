<?php

/* Este archivo es un template con el formato de e-mail para el blanqueo de password

    Tener cuidado que los email client no soportan CSS => hay que escribirlos inline
    http://stackoverflow.com/questions/12751147/how-to-send-an-email-that-has-html-and-css-in-php   */

?>

<html xmlns="http://www.w3.org/1999/html">
<head>

</head>
<body>

    <div style="background-color: #000000">
        <img style="padding-left: 10px" src="cid:logo_2u">
    </div>

    <div id="contenido" style="background-color: lavender; padding: 10px">

        <p style='font-weight: bold'><?php //echo $emp[0]['APELLIDO'] ?> <?php //echo $emp[0]['NOMBRE'] ?>:</p>
        <p>Por la presente le informamos su usuario y contraseña de acceso al sistema de capacitación de Innovisión SA.</p>

        <p><span style="font-weight: bold">Usuario: </span><?php //echo $body_usuario ?></p>
        <p><span style="font-weight: bold">Contraseña: </span><?php //echo $body_pass ?></p>
        <p>El sistema al acceder por primera vez le exigira cambiar la contraseña para su seguridad.</p>
        <br/>
        <p>Mensaje enviado desde <a href="<?php //echo $GLOBALS['ini']['app_url']; ?>"><?php //echo $GLOBALS['ini']['app_name']; ?></a></p>

    </div>

</body>
</html>

