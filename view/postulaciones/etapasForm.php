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
            //alert('editar etapa: '+id);
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

            //alert('guardar etapa');

            //if ($("#postulacion-form").valid()){

                var params={};
                params.action = 'etapas';
                params.operation = 'saveEtapa';
                params.id_etapa = $('#id_etapa').val();
                params.etapa = $('#etapa').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.comentarios = $('#comentarios').val();
                //params.id_postulante = $('#id_postulante').val();
                //params.origen_cv = $('#origen_cv').val();
                //params.expectativas = $('#expectativas').val();
                //params.propuesta_economica = $('#propuesta_economica').val();
                //alert(params.id_etapa);

                $.post('index.php',params,function(data, status, xhr){

                    //objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#etapa-form button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Etapa guardada con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side').load('index.php',{action:"etapas", operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#etapa-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la etapa').addClass('alert alert-danger').show();
                    }

                }, 'json');

            //}
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

                            <button class="btn btn-primary btn-sm pull-right" id="add" name="add" type="submit" title="Agregar etapa">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>

                            <?php include_once('view/postulaciones/etapasGrid.php');?>
                        </div>

                        <div class="col-md-6" id="etapas_right_side">

                        </div>


                </div>


                <!--<div id="myElem" class="msg" style="display:none"></div>-->

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



