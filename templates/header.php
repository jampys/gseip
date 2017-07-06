<header>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">SEIP</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <!--<li class="active"><a href="#">Home</a></li>-->
                    <li><a href="#">Page 1</a></li>
                    <li><a href="#">Page 2</a></li>
                    <li><a href="#">Page 3</a></li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Administración
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?action=clientes&operation=index">Clientes</a></li>
                            <li><a href="index.php?action=empleados">Empleados</a></li>
                            <li><a href="#">JavaScript</a></li>
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