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



        $('#myModal').on('click', '#submit',function(){ //ok

            //if ($("#busqueda-form").valid()){

                var jsonCuadrillas = [];

                //alert('presiono en submit');

                $('.cu_cuadrilla').each(function(){ //recorre c/u de las cuadrillas
                    //alert('encontro cuadrilla');

                    if($(this).find('.cu_selected').prop('checked')){ //si la cuadrilla esta seleccionada para guardarse
                        item = {};
                        item.id_cuadrilla = $(this).attr('id_cuadrilla');
                        item.id_contrato = $(this).attr('id_contrato');
                        item.cuadrilla = $(this).find('.cu_cuadrilla').text();
                        item.id_empleado_1 = $(this).find('.cu_id_empleado_1 option:selected').val();
                        item.id_empleado_2 = $(this).find('.cu_id_empleado_2 option:selected').val();
                        item.id_area = $(this).find('.cu_id_area option:selected').val();
                        item.id_vehiculo = $(this).find('.cu_id_vehiculo option:selected').val();
                        item.id_evento = $(this).find('.cu_id_evento option:selected').val();

                        jsonCuadrillas.push(item);
                    }

                });

                //alert(jsonCuadrillas[0].nombre);
                //throw new Error();


                var params={};
                params.action = 'partes';
                params.operation = 'insertPartes';
                params.fecha_parte = $('#add_fecha').val();
                params.vCuadrillas = JSON.stringify(jsonCuadrillas);
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;

                $.post('index.php',params,function(data, status, xhr){

                    //objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Partes guardados con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar los partes').addClass('alert alert-danger').show();
                    }

                }, 'json');

            //}
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

                        <div class="row cu_cuadrilla" id_cuadrilla="<?php echo $cu['id_cuadrilla'] ?>" id_contrato="<?php echo $cu['id_contrato'] ?>">

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12 cu_cuadrilla"><?php echo $cu['nombre'] ?></div>
                                </div>
                            </div>



                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick cu_id_empleado_1" data-live-search="true" data-size="5">
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
                                            <select class="selectpicker form-control show-tick cu_id_empleado_2" data-live-search="true" data-size="5">
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
                                            <select class="selectpicker form-control show-tick cu_id_area" data-live-search="true" data-size="5">
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
                                            <select class="selectpicker form-control show-tick cu_id_vehiculo" data-live-search="true" data-size="5">
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
                                            <select class="selectpicker form-control show-tick cu_id_evento" data-live-search="true" data-size="5">
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
                                                <input type="checkbox" class="cu_selected">
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



