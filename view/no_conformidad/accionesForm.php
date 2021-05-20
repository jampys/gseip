<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#etapas_left_side').on('click', '.edit', function(){
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
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

        $('#etapas_left_side').on('click', '.view', function(){
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_etapa = id;
            params.action = "etapas";
            params.operation = "editEtapa";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#etapa-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar nueva accion
        $('#etapas_left_side').on('click', '#add', function(){
            params={};
            params.action = "nc_acciones";
            params.operation = "newAccion";
            params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Guardar etapa luego de ingresar nueva o editar
        $('#myModal').on('click', '#submit',function(){
            //alert('guardar etapa');

            if ($("#etapa-form").valid()){

                var params={};
                params.action = 'etapas';
                params.operation = 'saveEtapa';
                params.id_etapa = $('#id_etapa').val();
                params.id_postulacion = $('#id_postulacion').val();
                params.fecha_etapa = $('#fecha_etapa').val();
                params.etapa = $('#etapa').val();
                params.aplica = $('input[name=aplica]:checked').val();
                params.motivo = $('#motivo').val();
                params.modo_contacto = $('#modo_contacto').val();
                params.comentarios = $('#comentarios').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#etapa-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Etapa guardada con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side .grid').load('index.php',{action:"etapas", id_postulacion:params.id_postulacion, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#etapa-form').hide();
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar la etapa').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        var dialog;
        $('#etapas_left_side').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la etapa?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrar(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrar = function(id) {
            //alert(id);
            params={};
            params.id_etapa = id;
            params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            params.action = "etapas";
            params.operation = "deleteEtapa";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Etapa eliminada con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#etapa-form').hide();
                        $('#etapas_left_side .grid').load('index.php',{action:"etapas", id_postulacion:params.id_postulacion, operation:"refreshGrid"});
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la etapa</div>');

            });

        };



        //evento al salir o cerrar con la x el modal de acciones
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            $("#search").trigger("click");
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

                            <div class="clearfix">
                                <button class="btn btn-default pull-right" id="add" name="add" type="submit" title="Agregar etapa">
                                    <span class="glyphicon glyphicon-plus dp_green"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/no_conformidad/accionesGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-6" id="etapas_right_side">

                        </div>


                </div>


                <!--<div id="myElem" class="msg" style="display:none"></div>-->

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



