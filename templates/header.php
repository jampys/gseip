<script type="text/javascript">

    $(document).ready(function(){

        $(document).on('click', '#about', function(){ //ok
            //preparo los parametros
            params={};
            params.action = "index";
            params.operation = "about";
            $('#header_popupbox').load('index.php', params,function(){
                $('#myModal').modal();
            })

        });


    });

</script>


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
                            <li class="dropdown-header">PERSONAL</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('EMP_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=empleados">Empleados</a></li>
                            <?php } ?>

                            <?php if ( PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=puestos">Puestos</a></li>
                            <?php } ?>

                            <?php if ( PrivilegedUser::dhasPrivilege('CON_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=contratos"><i class="fas fa-suitcase fa-fw"></i>&nbsp;Contratos</a></li>
                            <?php } ?>
                            <li><a href="index.php?action=organigramas">Organigrama <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">VEHICULAR</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=vehiculos"><i class="fas fa-car fa-fw"></i>&nbsp;Vehículos</a></li>
                            <?php } ?>


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
                            <li><a href="#" style="cursor: not-allowed">Plan de capacitación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <!--<li><a href="#">Capacitaciones</a></li>-->
                            <li><a href="#" style="cursor: not-allowed">Capacitaciones <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li><a href="#" style="cursor: not-allowed">Cursos <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li><a href="#" style="cursor: not-allowed">Estadísticas <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Desarrollo<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" style="cursor: not-allowed">Plan de evaluación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php if ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) ) { ?>
                                <!--<li><a href="index.php?action=objetivos">Objetivos</a></li>-->
                                <li><a href="index.php?action=obj_objetivos">Objetivos<span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php } ?>
                            <?php //if ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=evaluaciones">Evaluación de desempeño</a></li>
                            <?php //} ?>
                        </ul>
                    </li>


                    <?php if ( PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) || PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) || PrivilegedUser::dhasPrivilege('PTN_VER', array(1))  ) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Selección<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->
                            <?php if ( PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=busquedas"><i class="far fa-clipboard fa-fw"></i>&nbsp;Búsquedas</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('PTE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=postulantes"><i class="far fa-id-badge fa-fw"></i>&nbsp;Postulantes</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('PTN_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=postulaciones"><i class="fas fa-tasks fa-fw"></i>&nbsp;Postulaciones</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vencimientos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">PERSONAL</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('RPE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=renovacionesPersonal"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Vencimientos de personal</a></li>
                            <?php } ?>

                            <li role="separator" class="divider"></li>

                            <li class="dropdown-header">VEHICULAR</li>

                            <?php if ( PrivilegedUser::dhasPrivilege('RVE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=renovacionesVehiculos"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Vencimientos de vehículos</a></li>
                            <?php } ?>
                            <?php if ( PrivilegedUser::dhasPrivilege('RVE_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=vto_gruposVehiculos"><i class="fas fa-users fa-sm fa-fw"></i>&nbsp;Grupos de vehículos</a></li>
                            <?php } ?>
                        </ul>
                    </li>



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Novedades<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">SUCESOS DE PERSONAL</li>
                            <?php if ( PrivilegedUser::dhasPrivilege('SUC_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=sucesos"><i class="far fa-calendar-alt fa-fw"></i>&nbsp;Sucesos <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php } ?>

                            <li role="separator" class="divider"></li>

                            <li class="dropdown-header">ACTIVIDAD CUADRILLA</li>
                            <?php //if ( PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) ) { ?>
                            <li><a href="index.php?action=cuadrillas"><i class="fas fa-car fa-fw"></i>&nbsp;Cuadrillas <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php //} ?>

                            <?php //if ( PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) ) { ?>
                                <li><a href="index.php?action=partes"><i class="fas fa-car fa-fw"></i>&nbsp;Partes diarios cuadrilla <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php //} ?>

                        </ul>
                    </li>




                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                        <a href="#" title="Ayuda" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-question-circle fa-lg"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="dropdown-header">AYUDA</li>
                            <!--<li><a id="about" href="index.php?action=login&operation=salir">Acerca de</a></li>-->
                            <li><a id="about" href="#">Acerca de</a></li>
                        </ul>

                    </li>

                    <li class="dropdown">
                        <a href="#" title="Tu perfil y configuración" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <!--<span class="glyphicon glyphicon-user"></span>
                            Usuario
                            <span class="caret"></span>-->
                            <i class="fas fa-user fa-lg"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="dropdown-header">USUARIO</li>
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

<div id="header_popupbox"></div>