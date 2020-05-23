<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            //alert('editar rol de usuario');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_user_role = id;
            params.action = "sec_user-role";
            params.operation = "editRole";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#role-form #id_role").prop("disabled", true).selectpicker('refresh');
                //$('#myModal').modal();
            })
        });


        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_user_role = id;
            params.action = "sec_user-role";
            params.operation = "editRole";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#role-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            });
        });



        //Abre formulario para ingresar un nuevo rol al usuario
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "sec_user-role";
            params.operation = "newRole";
            params.id_user = $('#etapas_left_side #add').attr('id_user');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_user').val(params.id_user);
            })
        });


        //Guardar rol-usuario luego de ingresar nuevo o editar
        $('#myModal').on('click', '#submit',function(){  //ok
            //alert('guardar rol-usuario');

            if ($("#role-form").valid()){

                var params={};
                params.action = 'sec_user-role';
                params.operation = 'saveRole';
                params.id_user = $('#id_user').val();
                params.id_user_role = $('#id_user_role').val();
                params.id_role = $('#id_role').val();
                params.fecha_desde = $('#fecha_desde').val();
                params.fecha_hasta = $('#fecha_hasta').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#role-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Rol guardado con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side .grid').load('index.php',{action:"sec_user-role", id_user:params.id_user, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#role-form').hide();
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('No es posible guardar el rol').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        $('#etapas_left_side').on('click', '.delete', function(){ //ok
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
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

                ],
                open: function() {
                    $(this).html(confirmMessage('¿Desea eliminar el rol al usuario?'));
                }
            }).dialog('open');
            return false;
        });


        $.fn.borrarGv = function(id) { //ok
            //alert(id);
            //preparo los parametros
            params={};
            params.id_user_role = id;
            params.id_user = $('#etapas_left_side #add').attr('id_user');
            params.action = "sec_user-role";
            params.operation = "deleteRole";
            //alert(params.id_grupo);
            //throw new Error();

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm #myElemento").html('Rol eliminado con exito').addClass('alert alert-success').show();
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm #myElemento").hide();
                                            $('#role-form').hide();
                                            $('#confirm').dialog('close');
                                            $('#etapas_left_side .grid').load('index.php',{action:"sec_user-role", id_user:params.id_user, operation:"refreshGrid"});
                                          }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar el rol').addClass('alert alert-danger').show();
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
                                <?php include_once('view/usuarios/rolesGrid.php');?>
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





