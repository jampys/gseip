<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-etp').dialog({
            autoOpen: false
            //modal: true,
        });


        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_grupo_vehiculo = id;
            params.action = "vto_grupo-vehiculo";
            params.operation = "editVehiculo";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
            })
        });


        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_grupo_vehiculo = id;
            params.action = "vto_grupo-vehiculo";
            params.operation = "editVehiculo";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#etapas_right_side fieldset").prop("disabled", true);
                $("#etapa-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo vehiculo al grupo
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "vto_grupo-vehiculo";
            params.operation = "newVehiculo";
            params.id_grupo = $('#etapas_left_side #add').attr('id_grupo');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_grupo').val(params.id_grupo);
            })
        });


        //Guardar grupo-vehiculo luego de ingresar nuevo o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar grupo-vehiculo');

            if ($("#grupo-vehiculo-form").valid()){

                var params={};
                params.action = 'vto_grupo-vehiculo';
                params.operation = 'saveVehiculo';
                params.id_grupo_vehiculo = $('#id_grupo_vehiculo').val();
                params.id_grupo = $('#id_grupo').val();
                params.id_vehiculo = $('#id_vehiculo').val();
                params.fecha_desde = $('#fecha_desde').val();
                params.fecha_hasta = $('#fecha_hasta').val();
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#grupo-vehiculo-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Vehículo guardado con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side .grid').load('index.php',{action:"vto_grupo-vehiculo", id_grupo:params.id_grupo, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#grupo-vehiculo-form').hide();
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el vehículo').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        //$(document).on('click', '#example .delete', function(){
        $('#etapas_left_side').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-etp').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar(id);
                        },
                        class:"btn btn-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"btn btn-default"
                    }

                ]
            }).dialog('open');
            return false;
        });


        $.fn.borrar = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_etapa = id;
            params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            params.action = "etapas";
            params.operation = "deleteEtapa";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-etp #myElemento").html('Etapa eliminada con exito').addClass('alert alert-success').show();
                    $('#etapas_left_side .grid').load('index.php',{action:"etapas", id_postulacion:params.id_postulacion, operation:"refreshGrid"});
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-etp #myElemento").hide();
                                            $('#etapa-form').hide();
                                            $('#confirm-etp').dialog('close');
                                          }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar la etapa').addClass('alert alert-danger').show();
                }


            });

        };



        //evento al salir o cerrar con la x el modal de etapas
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
                                <button class="btn btn-primary btn-sm pull-right" id="add" name="add" type="submit" title="Agregar grupo">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/grupos_vehiculos/vehiculosGrid.php');?>
                            </div>

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



<div id="confirm-etp">
    <div class="modal-body">
        ¿Desea eliminar la etapa?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


