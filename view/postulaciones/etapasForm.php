<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            //var id = $(this).closest('tr').attr('data-id');
            var id = $(this).attr('data-id');
            alert('editar etapa: '+id);
            params={};
            params.id_etapa = id;
            params.action = "etapas";
            params.operation = "editEtapa";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });



        $('#myModal').on('click', '#submit',function(){

            alert('guardar etapa');

            if ($("#postulacion-form").valid()){

                var params={};
                params.action = 'postulaciones';
                params.operation = 'savePostulacion';
                params.id_postulacion = $('#id_postulacion').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.id_busqueda = $('#id_busqueda').val();
                params.id_postulante = $('#id_postulante').val();
                params.origen_cv = $('#origen_cv').val();
                params.expectativas = $('#expectativas').val();
                params.propuesta_economica = $('#propuesta_economica').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Postulación guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            $('#myModal').modal('hide');
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la postulación').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });








    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <div class="row">

                        <div class="col-md-6" id="etapas_left_side">




                                <table class="table table-condensed dataTable table-hover">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Etapa</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($view->etapas as $et): ?>
                                        <tr>
                                            <td><?php echo $et['fecha'];?></td>
                                            <td><?php echo $et['etapa'];?></td>
                                            <td class="text-center"><a class="view" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="ver"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
                                            <td class="text-center"><a class="<?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>





                            <!--<table class="table table-condensed dataTable table-hover" id="puestos-table">
                                <thead>
                                <tr>
                                    <th class="col-md-1">Cod.</th>
                                    <th class="col-md-10">Nombre</th>
                                    <th class="col-md-1 text-center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>-->



                        </div>


                        <div class="col-md-6" id="etapas_right_side">







                        </div>


                    </div>


                <div id="myElem" class="msg" style="display:none"></div>

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



