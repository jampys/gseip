<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        $('.image').viewer({});

















    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >



                <form name ="postulante-form" id="postulante-form" method="POST" action="index.php">
                    <input type="hidden" name="id_postulante" id="id_postulante" value="<?php //print $view->postulante->getIdPostulante() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="apellido">Apellido</label>
                        <input class="form-control" type="text" name="apellido" id="apellido" value = "<?php //print $view->postulante->getApellido() ?>" placeholder="Apellido">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->postulante->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="dni">DNI</label>
                        <input class="form-control" type="text" name="dni" id="dni" value = "<?php print $view->postulante->getDni() ?>" placeholder="DNI">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="telefono">Teléfono</label>
                        <input class="form-control" type="text" name="telefono" id="telefono" value = "<?php print $view->postulante->getTelefono() ?>" placeholder="Teléfono">
                    </div>

                    <div class="form-group">
                        <label for="id_localidad" class="control-label">Ubicación</label>
                        <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" title="Seleccione la localidad" data-live-search="true" data-size="5">
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>"
                                    <?php echo ($loc['id_localidad'] == $view->postulante->getIdLocalidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="formacion" class="control-label">Formación</label>
                        <select class="form-control selectpicker show-tick" id="formacion" name="formacion" title="Seleccione la formación"  data-live-search="true" data-size="5">
                            <?php foreach ($view->formaciones['enum'] as $fo){
                                ?>
                                <option value="<?php echo $fo; ?>"
                                    <?php echo ($fo == $view->postulante->getFormacion() OR ($fo == $view->formaciones['default'] AND !$view->formacion->getFormacion()) )? 'selected' :'' ?>
                                    >
                                    <?php echo $fo; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="id_especialidad" class="control-label">Especialidad</label>
                        <select class="form-control selectpicker show-tick" id="id_especialidad" name="id_especialidad" title="Seleccione la especialidad" data-live-search="true" data-size="5">
                            <?php foreach ($view->especialidades as $es){
                                ?>
                                <option value="<?php echo $es['id_especialidad']; ?>"
                                    <?php echo ($es['id_especialidad'] == $view->postulante->getIdEspecialidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $es['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lista_negra" name="lista_negra" <?php echo (!$view->postulante->getListaNegra())? '' :'checked' ?> ><a href="#" title="Seleccione para incluir al postulante en la lista negra">Agregar a lista negra</a>
                            </label>
                        </div>
                    </div>


                </form>



</fieldset>



