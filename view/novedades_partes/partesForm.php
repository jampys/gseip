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



        $('#myModal').on('click', '#submit',function(){

            if ($("#busqueda-form").valid()){

                var params={};
                params.action = 'busquedas';
                params.operation = 'saveBusqueda';
                params.id_busqueda = $('#id_busqueda').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.nombre = $('#nombre').val();
                params.fecha_apertura = $('#fecha_apertura').val();
                params.fecha_cierre = $('#fecha_cierre').val();
                params.id_puesto = $('#id_puesto').val();
                params.id_localidad = $('#id_localidad').val();
                params.id_contrato = $('#id_contrato').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    //objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Búsqueda guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la búsqueda').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#myModal #cancel').on('click', function(){
           //alert('cancelar');
            //uploadObj.stopUpload();
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#busqueda-form').validate({
            rules: {
                nombre: {required: true},
                fecha_apertura: {required: true}
                /*fecha_emision: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaEmision",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                },
                fecha_vencimiento: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaVencimiento",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            fecha_vencimiento: function(){ return $('#fecha_vencimiento').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }*/

            },
            messages:{
                nombre: "Ingrese el nombre",
                fecha_apertura: "Seleccione la fecha de apertura"
                /*fecha_emision: {
                    required: "Ingrese la fecha de emisión",
                    remote: "La fecha de emisión debe ser mayor"
                },
                fecha_vencimiento: {
                    required: "Ingrese la fecha de vencimiento",
                    remote: "La fecha de vencimiento debe ser mayor"
                }*/
            }

        });



    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">



                <?php if(isset($view->cuadrillas) && sizeof($view->cuadrillas) > 0) {?>

                    <?php foreach ($view->cuadrillas as $cu): ?>

                        <div class="row">

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12"><?php echo $cu['nombre'] ?></div>
                                </div>
                            </div>



                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                                                <option value="">Seleccione un empleado</option>
                                                <?php foreach ($view->empleados as $ar){ ?>
                                                    <option value="<?php echo $ar['id_empleado']; ?>"
                                                        <?php echo ($ar['id_empleado'] == $cu['empleado_1'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['apellido'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                                                <option value="">Seleccione un empleado</option>
                                                <?php foreach ($view->empleados as $ar){ ?>
                                                    <option value="<?php echo $ar['id_empleado']; ?>"
                                                        <?php echo ($ar['id_empleado'] == $cu['empleado_2'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['apellido'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-2" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                                                <option value="">Seleccione un Área</option>
                                                <?php foreach ($view->areas as $ar){ ?>
                                                    <option value="<?php echo $ar['id_area']; ?>"
                                                        <?php echo ($ar['id_area'] == $cu['default_id_area'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick" id="search_vehiculo" name="search_vehiculo" data-live-search="true" data-size="5">
                                                <option value="">Seleccione un Vehículo</option>
                                                <?php foreach ($view->vehiculos as $ar){ ?>
                                                    <option value="<?php echo $ar['id_vehiculo']; ?>"
                                                        <?php echo ($ar['id_vehiculo'] == $cu['default_id_vehiculo'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['nro_movil'].' '.$ar['modelo']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick" id="search_vehiculo" name="search_vehiculo" data-live-search="true" data-size="5">
                                                <option value="">Seleccione un evento</option>
                                                <?php foreach ($view->eventos as $ar){ ?>
                                                    <option value="<?php echo $ar['id_evento']; ?>">
                                                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>



                                </div>
                            </div>


                            <div class="col-md-1">
                                <div class="row">

                                    <div class="col-md-12">
                                        <!--<a class="<?php echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </a>-->
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="btn-primary">
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>




                    <?php endforeach; ?>

                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> La cuadrilla aún no tiene empleados registrados.
                    </div>

                <?php } ?>



                <div id="myElem" class="msg" style="display:none"></div>








            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



