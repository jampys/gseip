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

                    <!--<li><a href="index.php?action=empleados">Empleados</a></li>
                    <li><a href="index.php?action=contratos">Contratos</a></li>-->

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estructura<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->
                            <?php if ( PrivilegedUser::dhasPrivilege('EMP_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=empleados">Empleados</a></li>
                            <?php } ?>

                            <?php if ( PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=puestos">Puestos</a></li>
                            <?php } ?>

                            <li><a href="index.php?action=contratos">Contratos</a></li>
                            <li><a href="#" style="cursor: not-allowed">Organigrama <span class="text-muted"><small> [En construcción]</small></span></a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">HABILIDADES Y COMPETENCIAS</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('HAB_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=habilidades">Habilidades</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('HEM_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=habilidad-empleado">Habilidades por Empleado</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('HPU_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=habilidad-puesto">Habilidades por puesto</a></li>
                            <?php } ?>

                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Capacitación<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->
                            <li><a href="#" style="cursor: not-allowed">Plan de capacitación <span class="text-muted"><small> [En construcción]</small></span></a></li>
                            <!--<li><a href="#">Capacitaciones</a></li>-->
                            <li><a href="#" style="cursor: not-allowed">Capacitaciones <span class="text-muted"><small> [En construcción]</small></span></a></li>
                            <li><a href="#" style="cursor: not-allowed">Cursos <span class="text-muted"><small> [En construcción]</small></span></a></li>
                            <li><a href="#" style="cursor: not-allowed">Estadísticas <span class="text-muted"><small> [En construcción]</small></span></a></li>
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Desarrollo<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" style="cursor: not-allowed">Plan de evaluación <span class="text-muted"><small> [En construcción]</small></span></a></li>
                            <?php if ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=objetivos">Objetivos</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=evaluaciones">Evaluaciones</a></li>
                            <?php } ?>
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Selección<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->
                            <li><a href="#" style="cursor: not-allowed">Registros <span class="text-muted"><small> [En construcción]</small></span></a></li>

                        </ul>
                    </li>



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vencimientos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">PERSONAL</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('RPE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=renovacionesPersonal"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Renovaciones</a></li>
                            <?php } ?>

                            <li role="separator" class="divider"></li>

                            <li class="dropdown-header">VEHICULAR</li>
                            <!--<li><a href="index.php?action="><i class="fas fa-car fa-fw"></i>&nbsp;Vehículos</a></li>-->
                            <li><a href="index.php?action=" style="cursor: not-allowed"><i class="fas fa-car fa-fw"></i>&nbsp;Vehículos <span class="text-muted"><small> [En construcción]</small></span></a>
                            <li><a href="index.php?action=" style="cursor: not-allowed"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Renovaciones <span class="text-muted"><small> [En construcción]</small></span></a></li>

                        </ul>
                    </li>

                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span>
                                Usuario
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="#"><span class="text-muted"><?php echo $_SESSION['user'] ?></span> </a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="index.php?action=login&operation=salir"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
                        </ul>

                    </li>

                </ul>

            </div>
        </div>
    </nav>

</header>