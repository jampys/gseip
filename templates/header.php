<style>
    #brand-image{
        height: 120%;
    }
</style>

<header>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <img id="brand-image" src="resources/img/seip140x40.png">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <!--<li class="active"><a href="#">Home</a></li>-->
                    <li><a href="index.php?action=clientes&operation=index">Clientes</a></li>
                    <li><a href="index.php?action=empleados">Empleados</a></li>
                    <li><a href="index.php?action=contratos">Contratos</a></li>



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administración<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?action=habilidades">Habilidades</a></li>
                            <li><a href="index.php?action=habilidad-empleado">Habilidades - Empleados</a></li>
                            <li><a href="index.php?action=puestos">Puestos</a></li>
                            <li><a href="index.php?action=habilidad-puesto">Habilidades - Puestos</a></li>
                            <li><a href="index.php?action=objetivos">Objetivos</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">NAV HEADER</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>





                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <!--<li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>-->
                    <li><a href="index.php?action=login&operation=salir"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

</header>