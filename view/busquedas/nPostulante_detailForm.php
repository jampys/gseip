<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('#postulante-form').validate({ //ok
            rules: {
                apellido: {required: function(){return $('#etapas_right_side').data('nuevo') == 1;}},
                nombre: {required: function(){return $('#etapas_right_side').data('nuevo') == 1;}},
                dni: {
                    //required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "postulantes",
                            operation: "checkDni",
                            dni: function(){ return $('#dni').val();},
                            id_postulante: function(){ return $('#id_postulante').val();}
                        }
                    }
                },
                comentarios: {maxlength: 500}

            },
            messages:{
                apellido: "Ingrese el apellido",
                nombre: "Ingrese el nombre",
                dni: {
                    //required: "Ingrese el DNI",
                    remote: "El postulante ya se encuentra registrado"
                },
                comentarios: "Máximo 500 caracteres"

            }

        });



    });

</script>



<form name ="postulante-form" id="postulante-form" method="POST" action="index.php">

                    <!--<input type="hidden" name="id_postulante" id="id_postulante" value="<?php //print $view->postulante->getIdPostulante() ?>">-->

                    <div class="form-group required">
                        <label class="control-label" for="apellido">Apellido</label>
                        <input class="form-control" type="text" name="apellido" id="apellido" value = "<?php //print $view->postulante->getApellido() ?>" placeholder="Apellido">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php //print $view->postulante->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="dni">DNI</label>
                        <input class="form-control" type="text" name="dni" id="dni" value = "<?php //print $view->postulante->getDni() ?>" placeholder="DNI">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="telefono">Teléfono</label>
                        <input class="form-control" type="text" name="telefono" id="telefono" value = "<?php //print $view->postulante->getTelefono() ?>" placeholder="Teléfono">
                    </div>

                    <div class="form-group">
                        <label for="id_localidad" class="control-label">Ubicación</label>
                        <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" title="Seleccione la localidad" data-live-search="true" data-size="5">
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>"
                                    <?php //echo ($loc['id_localidad'] == $view->postulante->getIdLocalidad())? 'selected' :'' ?>
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
                                    <?php //echo ($fo == $view->postulante->getFormacion() OR ($fo == $view->formaciones['default'] AND !$view->formacion->getFormacion()) )? 'selected' :'' ?>
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
                                    <?php //echo ($es['id_especialidad'] == $view->postulante->getIdEspecialidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $es['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <!--<div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lista_negra" name="lista_negra" <?php //echo (!$view->postulante->getListaNegra())? '' :'checked' ?> ><a href="#" title="Seleccione para incluir al postulante en la lista negra">Agregar a lista negra</a>
                            </label>
                        </div>
                    </div>-->


                    <div class="form-group">
                        <label class="control-label" for="comentarios">Comentarios</label>
                        <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php //print $view->postulante->getComentarios(); ?></textarea>
                    </div>


</form>

                <div id="fileuploader">Upload</div>



                <!--<div id="myElem" class="msg" style="display:none"></div>-->







