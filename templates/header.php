<script type="text/javascript">

    $(document).ready(function(){

        function setFavicons(favImg){
            let headTitle = document.querySelector('head');
            let setFavicon = document.createElement('link');
            setFavicon.setAttribute('rel','shortcut icon');
            setFavicon.setAttribute('href',favImg);
            headTitle.appendChild(setFavicon);}
        setFavicons('resources/img/favicon.ico');


        $(document).on('click', '#about', function(){ //ok
            //preparo los parametros
            params={};
            params.action = "index";
            params.operation = "about";
            $('#header_popupbox').load('index.php', params,function(){
                $('#myModal').modal();
            });
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
      /* por defecto padding derecho e izquierdo de 15px y abajo y arriba de 9px */
      padding: 2px 15px 2px 15px;

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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administración<span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <?php if( PrivilegedUser::dhasPrivilege('EMP_VER', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('CON_VER', array(1))
                                    ){ ?>
                                <li class="dropdown-header"><i class="fas fa-users fa-fw dp_gray"></i>&nbsp;PERSONAL</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('EMP_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=empleados">Empleados</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PUE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=puestos">Puestos</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('CON_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=contratos">Contratos</a></li>
                            <?php } ?>
                                <li><a href="index.php?action=organigramas">Organigrama <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>


                            <?php if( PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('GRV_VER', array(1))
                                    ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header"><i class="fas fa-car fa-fw dp_gray"></i>&nbsp;VEHICULOS</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('VEH_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=vehiculos">Vehículos</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=vto_gruposVehiculos">Flotas de vehículos</a></li>
                            <?php } ?>

                            <?php if( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">SEGURIDAD</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('USR_ABM', array(1)))? '': 'disabled' ?>"><a href="index.php?action=sec_users">Usuarios</a></li>
                                <li class="disabled"><a href="#">Roles <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                            <?php } ?>

                            <?php if( PrivilegedUser::dhasPrivilege('PER_ABM', array(1)) ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">NOVEDADES</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PER_ABM', array(1)))? '': 'disabled' ?>"><a href="index.php?action=nov_periodos">Períodos</a></li>
                            <?php } ?>

                        </ul>
                    </li>



                    <?php if( PrivilegedUser::dhasPrivilege('HAB_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('HEM_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('HPU_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('CAP_VER', array(1))
                            ){ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Capacitación<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="disabled"><a href="#">Plan de capacitación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('CAP_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=cap_capacitaciones">Capacitaciones</a></li>
                                <li class="disabled"><a href="#">Estadísticas <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>

                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">HABILIDADES Y COMPETENCIAS</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('HAB_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidades">Habilidades</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('HEM_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidad-empleado">Habilidades por Empleado</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('HPU_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=habilidad-puesto">Habilidades por puesto</a></li>
                            </ul>
                        </li>
                    <?php } ?>



                    <?php if( PrivilegedUser::dhasPrivilege('EAD_COM', array(1)) ||
                              PrivilegedUser::dhasPrivilege('EAD_AGS', array(1)) ||
                              PrivilegedUser::dhasPrivilege('EAD_REP', array(1)) ||
                              PrivilegedUser::dhasPrivilege('EAD_OBJ', array(1))
                            ){ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Desarrollo<span class="caret"></span></a>
                            <ul class="dropdown-menu">

                                <li class="disabled"><a href="#">Plan de evaluación <span class="text-muted text-danger"><small> [En construcción]</small></span></a></li>
                                <li class="<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(1)) ||
                                                        PrivilegedUser::dhasPrivilege('EAD_AGS', array(1)) ||
                                                        PrivilegedUser::dhasPrivilege('EAD_REP', array(1)) ||
                                                        PrivilegedUser::dhasPrivilege('EAD_OBJ', array(1))
                                                    )? '': 'disabled' ?>"><a href="index.php?action=evaluaciones">Evaluación de desempeño</a></li>

                            </ul>
                        </li>
                    <?php } ?>




                    <?php if( PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('PTE_VER', array(1))
                            ){ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Selección<span class="caret"></span></a>
                            <ul class="dropdown-menu">

                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('BUS_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=busquedas">Búsquedas</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PTE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=postulantes">Postulantes</a></li>

                            </ul>
                        </li>
                    <?php } ?>



                    <?php if( PrivilegedUser::dhasPrivilege('RPE_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('RVE_VER', array(1))
                            ){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vencimientos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header"><i class="fas fa-users fa-fw dp_gray"></i>&nbsp;PERSONAL</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RPE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesPersonal">Vencimientos de personal</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesPersonalAuditoria">Auditoría de personal</a></li>

                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header"><i class="fas fa-car fa-fw dp_gray"></i>&nbsp;VEHICULOS</li>

                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RVE_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesVehiculos">Vencimientos de vehículos</a></li>
                            <li class="<?php echo (PrivilegedUser::dhasPrivilege('RVE_ABM', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=renovacionesVehiculosAuditoria">Auditoría de vehículos</a></li>

                        </ul>
                    </li>
                    <?php } ?>



                    <?php if( PrivilegedUser::dhasPrivilege('PAR_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('SUC_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('CUA_VER', array(1))
                            ){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Novedades<span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <?php if( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('PAR_VER', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('CUA_VER', array(1))
                                    ){ ?>
                                <li class="dropdown-header">ACTIVIDAD CUADRILLA</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)))? '': 'disabled' ?>"><a href="index.php?action=novedades2">Carga de novedades</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=partes">Consulta de novedades</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('CUA_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=cuadrillas">Cuadrillas</a></li>
                            <?php } ?>

                            <?php if( PrivilegedUser::dhasPrivilege('SUC_VER', array(1)) ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">SUCESOS DE PERSONAL</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('SUC_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=sucesos">Sucesos</a></li>
                            <?php } ?>

                            <?php if( PrivilegedUser::dhasPrivilege('PAR_VER', array(1)) ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header"><i class="far fa-calendar-alt fa-fw dp_gray"></i>&nbsp;CALENDARIO</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_VER', array(1)))? '': 'disabled' ?>"><a href="index.php?action=nov_calendar">Calendario de actividad</a></li>
                            <?php } ?>


                            <?php if( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) ||
                                      PrivilegedUser::dhasPrivilege('PAR_ABM', array(1))
                                    ){ ?>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">HABILITAS</li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)))? '': 'disabled' ?>"><a href="index.php?action=habilitas">Conversores</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)))? '': 'disabled' ?>"><a href="index.php?action=habilitas-control">Consultas</a></li>
                            <?php } ?>

                        </ul>
                    </li>
                    <?php } ?>





                    <?php if( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) ||
                              PrivilegedUser::dhasPrivilege('NC_ABM', array(1))
                            ){ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CSMA<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=obj_objetivos">Objetivos</a></li>
                                <li class="<?php echo (PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? '': 'disabled' ?>"><a href="index.php?action=nc_no_conformidad">No conformidades</a></li>

                            </ul>
                        </li>
                    <?php } ?>



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

                            <img src="<?php echo $_SESSION['profile_picture'] ?>" class="profile-image img-circle">&nbsp;<?php echo $_SESSION['user'] ?>
                            <b class="caret"></b>

                        </a>

                        <ul class="dropdown-menu">
                            <!--<li class="dropdown-header">USUARIO</li>-->
                            <li><a href="#"><i class="fas fa-user dp_gray"></i>&nbsp;Mi perfil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="index.php?action=login&operation=salir"><span class="glyphicon glyphicon-log-out dp_gray"></span> Cerrar sesión</a></li>
                        </ul>

                    </li>

                </ul>

            </div>
        </div>
    </nav>

</header>

<div id="header_popupbox"></div>