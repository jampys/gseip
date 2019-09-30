<script type="text/javascript">


    $(document).ready(function(){


        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-ve').dialog({
            autoOpen: false
            //modal: true,
        });


        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            //alert('editar postulacion');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_postulacion = id;
            params.action = "busqueda-postulante";
            params.operation = "editPostulacion";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                $('#postulacion-form #id_postulante').attr('disabled', true).selectpicker('refresh');
                $("#chalampa #culo").css('display', 'none');
            })
        });


        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_postulacion = id;
            params.action = "busqueda-postulante";
            params.operation = "editPostulacion";
            params.target = "view";
            $('#etapas_right_side').load('index.php', params,function(){
                $("#etapas_right_side fieldset").prop("disabled", true);
                $("#chalampa #footer-buttons button").css('display', 'none');
                $("#chalampa #culo").css('display', 'none');
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo postulante a la busqueda
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "busqueda-postulante";
            params.operation = "newPostulacion";
            params.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_busqueda').val(params.id_busqueda);
            })
        });




        $('#etapas_left_side').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            $('#confirm-ve').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarGv(id);
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


        $.fn.borrarGv = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_postulacion = id;
            params.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
            params.action = "busqueda-postulante";
            params.operation = "deletePostulacion";
            //alert(params.id_grupo);
            //throw new Error();

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-ve #myElemento").html('Postulación eliminada con exito').addClass('alert alert-success').show();
                    $('#etapas_left_side .grid').load('index.php',{action:"busqueda-postulante", id_busqueda:params.id_busqueda, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-ve #myElemento").hide();
                                            $('#chalampa').hide();
                                            $('#confirm-ve').dialog('close');
                                          }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar el vehículo').addClass('alert alert-danger').show();
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

                <!--<input type="hidden" name="id_contrato" id="id_contrato" value="<?php //print $view->grupo->getIdVencimiento() ?>">-->
                
                <div class="row">

                        <div class="col-md-7" id="etapas_left_side">

                            <div class="clearfix">
                                <button <?php echo (PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)) )? '' : 'disabled' ?> class="btn btn-default pull-right dp_green" id="add" name="add" type="submit" title="Agregar vehículo">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/busquedas/nPostulacionesGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-5" id="etapas_right_side">

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



<div id="confirm-ve">
    <div class="modal-body">
        ¿Desea eliminar la postulación?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


