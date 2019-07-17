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


        $(".nav .disabled a").removeAttr("href");
        $(document).on('click', ".nav .disabled a", function(e) {
            //alert('aaa');
            //console.log('todo un link');
            e.preventDefault();
            return false;
        });


    });

</script>


<style>
    #brand-image{
        height: 120%;
    }

    .navbar-nav > li.profile > a img.profile-image {
        height: 30px;
        width: 30px;
    }

    .navbar-nav >li.profile > a {
        padding: 2px;
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

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estructura<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">PERSONAL</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('EMP_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=empleados">Empleados</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=puestos">Puestos</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('CON_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=contratos"><i class="fas fa-suitcase fa-fw"></i>&nbsp;Contratos</a></li>
                            <li><a href="index.php?action=organigramas">Organigrama <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">VEHICULAR</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=vehiculos"><i class="fas fa-car fa-fw"></i>&nbsp;Vehículos</a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">HABILIDADES Y COMPETENCIAS</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('HAB_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidades">Habilidades</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('HEM_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidad-empleado">Habilidades por Empleado</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('HPU_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidad-puesto">Habilidades por puesto</a></li>

                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Capacitación<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->

                            <li class="disabled"><a href="#">Plan de capacitación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li class="disabled"><a href="#">Capacitaciones <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li class="disabled"><a href="#">Cursos <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li class="disabled"><a href="#">Estadísticas <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>

                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Desarrollo<span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li class="disabled"><a href="#">Plan de evaluación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=obj_objetivos">Objetivos</a></li>
                            <li class="<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(1)) ||
                                                    PrivilegedUser::dhasPrivilege('EAD_AGS', array(1)) ||
                                                    PrivilegedUser::dhasPrivilege('EAD_REP', array(1)) ||
                                                    PrivilegedUser::dhasPrivilege('EAD_OBJ', array(1))
                                                  )? '': 'disabled' ?>"><a href="index.php?action=evaluaciones">Evaluación de desempeño</a></li>

                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Selección<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">RRHH</li>-->

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=busquedas"><i class="far fa-clipboard fa-fw"></i>&nbsp;Búsquedas</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('PTE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=postulantes"><i class="far fa-id-badge fa-fw"></i>&nbsp;Postulantes</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('PTN_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=postulaciones"><i class="fas fa-tasks fa-fw"></i>&nbsp;Postulaciones</a></li>

                        </ul>
                    </li>



                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vencimientos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">PERSONAL</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RPE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesPersonal"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Vencimientos de personal</a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">VEHICULAR</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RVE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesVehiculos"><i class="far fa-calendar-check fa-fw"></i>&nbsp;Vencimientos de vehículos</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=vto_gruposVehiculos"><i class="fas fa-users fa-sm fa-fw"></i>&nbsp;Grupos de vehículos</a></li>

                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Novedades<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">SUCESOS DE PERSONAL</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('SUC_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=sucesos"><i class="far fa-calendar-alt fa-fw"></i>&nbsp;Sucesos</a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">ACTIVIDAD CUADRILLA</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('CUA_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=cuadrillas"><i class="fas fa-car fa-fw"></i>&nbsp;Cuadrillas</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=partes"><i class="fas fa-car fa-fw"></i>&nbsp;Partes diarios cuadrilla</a></li>

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

                    <li class="dropdown profile">
                        <a href="#" title="Tu perfil y configuración" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

                            <img src="uploads/profile_user.jpg" class="profile-image img-circle">&nbsp;<?php echo $_SESSION['user'] ?>
                            <b class="caret"></b>

                        </a>

                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">USUARIO</li>-->
                            <li><a href="#"><span class="text-muted">Mi perfil</span> </a></li>
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