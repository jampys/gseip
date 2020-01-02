<style>

    #conceptos-table tbody .form-group{
        margin-bottom: 0px;

    }

    #conceptos-table tbody .culito{
        padding: 1px;

    }







</style>



<script type="text/javascript">


    $(document).ready(function(){

        $('.collapse').collapse(); //https://bootstrapdocs.com/v3.3.4/docs/javascript/#collapse

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        $('#confirm-ord, #confirm-suc').dialog({ //#confirm-emp, #confirm-ord, #confirm-con
            autoOpen: false
            //modal: true,
        });



        //Abre formulario para ingresar una nueva orden al parte
        $('#right_side').on('click', '#add-orden', function(){ //ok
            params={};
            params.action = "parte-orden";
            params.operation = "newOrden";
            params.origin = "nov2";
            params.id_parte = $('#id_parte').val();
            //alert(params.id_renovacion);
            $('#popupbox').load('index.php', params,function(){
                $('#myModal .alert').hide(); //oculta el alert del form orden_detailForm.php
                $('#myModal').modal();
            });
        });



        $('.grid-ordenes').on('click', '.edit', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "edit";
            params.origin = "nov2";
            /*$('#right_side').load('index.php', params,function(){
            })*/
            $('#popupbox').load('index.php', params,function(){
                $('#myModal .alert').hide(); //oculta el alert del form orden_detailForm.php
                $('#myModal').modal();
            });
        });


        //para ver orden de un parte
        $('.grid-ordenes').on('click', '.view', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "view";
            params.origin = "nov2";
            //alert(params.id_renovacion);
            $('#popupbox').load('index.php', params,function(){
                $('#myModal .alert').hide(); //oculta el alert del form orden_detailForm.php
                $("#myModal fieldset").prop("disabled", true);
                $("#orden-form #footer-buttons button").css('display', 'none');
                $('.selectpicker').selectpicker('refresh');
                $('#myModal').modal();
            });
        });


        //para clonar orden de un parte
        $('.grid-ordenes').on('click', '.clone', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "clone";
            params.origin = "nov2";
            //alert(params.id_renovacion);
            $('#popupbox').load('index.php', params,function(){
                $('#myModal .alert').hide(); //oculta el alert del form orden_detailForm.php
                $('#myModal').modal();
            });
        });


        //eliminar orden del parte
        $('.grid-ordenes').on('click', '.delete', function(){ 
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-ord').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarO(id);
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


        $.fn.borrarO = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_parte_orden = id;
            params.id_parte = $('#id_parte').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "parte-orden";
            params.operation = "deleteOrden";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-ord #myElemento").html('Orden eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-ord #myElemento").hide();
                        $('#orden-form').hide();
                        $('#confirm-ord').dialog('close');
                    }, 2000);
                }else{
                    $("#confirm-ord #myElemento").html('Error al eliminar la orden').addClass('alert alert-danger').show();
                }


            });

        };




        $('.grid-sucesos').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_suceso = id;
            params.action = "sucesos";
            params.operation = "editSuceso";
            params.target = "edit";
            //alert(params.id_renovacion);
            $('#popupbox').load('index.php', params,function(){
                $('#myModal').modal();
                $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                $('#id_evento').prop('disabled', true).selectpicker('refresh');
            })
        });



        $('.grid-sucesos').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_suceso = id;
            params.action = "sucesos";
            params.operation = "editSuceso";
            params.target = "view";
            $('#popupbox').load('index.php', params,function(){
                $("fieldset").prop("disabled", true);
                $('.selectpicker').selectpicker('refresh');
                $('.modal-footer').css('display', 'none');
                //$('#myModalLabel').html('');
                $('#myModal').modal();
            })

        });



        $('#right_side').on('click', '#add-suceso', function(){ //ok
            params={};
            params.id_empleado = $('#id_empleado').val();
            params.action = "sucesos";
            params.operation="newSuceso";
            $('#popupbox').load('index.php', params,function(){
                $('#myModal #id_empleado').val(params.id_empleado).prop('disabled', true).selectpicker('refresh');
                $('#myModal').modal();
            })
        });



        /*$(document).on('click', '#cancel',function(){
            $('#myModal').modal('hide');
        });*/




        $('.grid-sucesos').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
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


        $.fn.borrar = function(id) { //ok
            //alert(id);
            //preparo los parametros
            params={};
            params.id_suceso = id;
            params.action = "sucesos";
            params.operation = "deleteSuceso";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    $("#myElemento").html('Suceso eliminado con exito').addClass('alert alert-success').show();
                    //$('#content').load('index.php',{action:"habilidad-empleado", operation: "buscar", cuil: $("#cuil").val(), id_habilidad: $("#id_habilidad").val()});
                    $("#search").trigger("click");
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');
                    }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar el suceso').addClass('alert alert-danger').show();
                }

            }, 'json');

        };



        /**************************************************************************************/
        var jsonConceptos = [];


        //OBSOLETO. No se va a usar. Se reemplaza por el timepicker
        /*$('#conceptos-container').on('keypress', '.editable', function(e) {
            if (!(e.which >= 48 && e.which <= 57)) {
                return false;
            }
        });


        $('body').on('focus', '[contenteditable]', function() {
            const $this = $(this);
            $this.data('before', $this.html());
        }).on('blur keyup paste input', '[contenteditable]', function() {
            const $this = $(this);
            if ($this.data('before') !== $this.html()) {
                $this.data('before', $this.html());
                //$this.trigger('change');
                //alert('cambiooooo');
                var id = $(this).closest('tr').attr('id_parte_empleado_concepto');
                jsonConceptos[id].cantidad = $this.html();
            }
        });


        $.validator.addMethod("cMaxLength", $.validator.methods.maxlength, "Máx 50 caracteres");
        jQuery.validator.addClassRules({
            editable: {
                cMaxLength: 1
            }
        });*/

        $('#cantidad').timepicker({
            showMeridian: false,
            //defaultTime: false
            defaultTime: '00:00 AM'
        });




        $.cargarTablaConceptos = function(){

            if(Object.keys(jsonConceptos).length <= 0){

                $('#conceptos-container').append('<div class="alert alert-warning">'+
                                                 '<i class="fas fa-exclamation-triangle fa-fw"></i> El parte aún tiene conceptos registrados.'+
                                                 '</div>');

                    return; //finaliza la ejecucion de la funcion.
            }

            $('#conceptos-table tbody tr').remove();

            for (var i in jsonConceptos) {

                if (jsonConceptos[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }

                $('#conceptos-table tbody').append('<tr id_parte_empleado_concepto='+jsonConceptos[i].id_parte_empleado_concepto+'>' +
                 '<td>'+jsonConceptos[i].convenio+'</td>' +
                 '<td>'+jsonConceptos[i].concepto+'</td>' +
                 '<td>'+jsonConceptos[i].codigo+'</td>' +
                 '<td class="col-md-3 culito">' +
                    '<div class="form-group">'+
                        '<div class="input-group bootstrap-timepicker timepicker">'+
                            '<input type="text" class="form-control input-sm" value = "'+jsonConceptos[i].cantidad+'" id="prog_'+jsonConceptos[i].id_parte_empleado_concepto+'" name="prog_'+jsonConceptos[i].id_parte_empleado_concepto+'" >'+
                            '<span class="input-group-addon input-sm"><i class="glyphicon glyphicon-time"></i></span>'+
                        '</div>'+
                    '</div>' +
                '</td>' +
                 //'<td><div contenteditable="true" class="editable" id="prog_'+jsonConceptos[i].id_parte_empleado_concepto+'" name="prog_'+jsonConceptos[i].id_parte_empleado_concepto+'">'+jsonConceptos[i].cantidad+'</div></td>' +
                 '<td class="text-center">'+
                     '<a class="<?php echo (PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) /*&& $view->target!='view' && $ctos['tipo_calculo']=='M'*/)? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">'+
                         '<span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>'+
                     '</a>'+
                 '</td>'+
                 '</tr>');

                $("#prog_"+jsonConceptos[i].id_parte_empleado_concepto).timepicker({
                    showMeridian: false
                    //defaultTime: false
                });

            }

        };


        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "novedades2", "operation": "loadConceptos", "id_parte_empleado": $('#id_parte_empleado').val()},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){ //carga el array de empleados

                    //alert(data[indice]['id_proceso']);
                    var id = data[indice]['id_parte_empleado_concepto'];
                    jsonConceptos[id] = data[indice];

                    //var arr = [];
                    //$.each(data[indice]['id_proceso'], function(i, v){
                    //    arr.push(data[indice]['id_proceso'][i]['id_proceso']);
                    //});
                    //alert(arr);
                    //jsonEmpleados[id]['id_proceso'] = arr;
                    //jsonEmpleados[id]['id_proceso_old'] = arr;

                });

                $.cargarTablaConceptos();


            }

        });




        $('#empleado-form').on('click', '#submit',function(e){ //ok
            e.preventDefault();

            var jsonConceptosIx = [];
            for ( var item in jsonConceptos ){
                jsonConceptosIx.push( jsonConceptos[ item ] );
            }
            //params.vConceptos = JSON.stringify(jsonConceptosIx);
            var culito =  [];
            culito = JSON.stringify(jsonConceptosIx);
            //alert(culito);



            if ($("#empleado-form").valid()){

                throw new Error();

                var params={};
                params.action = 'parte-orden';
                params.operation = 'saveOrden';
                params.id_parte = $('#id_parte').val();
                params.id_parte_orden = $('#id_parte_orden').val();
                params.nro_parte_diario = $('#nro_parte_diario').val();
                params.orden_tipo = $('#orden_tipo').val();
                params.orden_nro = $('#orden_nro').val();
                params.hora_inicio = $('#hora_inicio').val();
                params.hora_fin = $('#hora_fin').val();
                params.servicio = $('#servicio').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#orden-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#orden-form #myElem").html('Orden guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#orden-form #myElem").hide();
                            //$('#myModal').modal('hide');
                            //$('#orden-form').hide();
                            $("#orden-form #cancel").trigger("click"); //para la modal (nov2)
                            $('#orden-form').hide(); //para la comun (nov)
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la órden').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });







    });

</script>

<div class="row">

    <div class="col-md-6" id="left_side">


        <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">




            <div class="alert alert-info">
                <strong><?php echo $view->label; ?></strong>
            </div>


            <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->
            <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php print $view->params['id_parte_empleado'] ?>">
            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->empleado->getIdEmpleado() ?>">



            <div class="form-group">
                <!--<label class="control-label" for="id_empleado">Cuadrilla</label>-->
                <select id="id_cuadrilla" name="id_cuadrilla" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione una cuadrilla">
                    <?php foreach ($view->cuadrillas as $cu){
                        ?>
                        <option value="<?php echo $cu['id_cuadrilla']; ?>"
                            <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                            >
                            <?php echo $cu['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>

            <div class="form-group">
                <!--<label for="id_evento" class="control-label">Evento</label>-->
                <select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">
                    <option value="">Seleccione un evento</option>
                    <?php foreach ($view->eventos as $ar){ ?>
                        <option value="<?php echo $ar['id_evento']; ?>"
                            <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                            >
                            <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>


            <div class="form-group required">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="conductor" name="conductor" <?php //echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Marcar la persona que maneja">Conductor</a>
                    </label>
                </div>
            </div>

            <hr/>
            <!-- SECCION DE CONCEPTOS -->

            <div class="form-group">
                <label for="id_evento" class="control-label">Conceptos</label>
                <select class="selectpicker form-control show-tick" id="id_ruta" name="id_ruta" data-live-search="true" data-size="5">
                    <option value="">Seleccione una ruta</option>
                    <?php foreach ($view->eventos as $ar){ ?>
                        <option value="<?php echo $ar['id_evento']; ?>"
                            <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                            >
                            <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>



            <div class="row">


                <div class="form-group col-md-6">
                    <!-- <label for="add_contrato" class="control-label">Nuevos partes</label>-->
                    <select class="form-control selectpicker show-tick" id="add_contrato" name="add_contrato" data-live-search="true" data-size="5">
                        <option value="">Seleccione un concepto</option>
                        <?php foreach ($view->contratos as $con){
                            ?>
                            <option value="<?php echo $con['id_contrato']; ?>" >
                                <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" class="form-control input-small hs-group" name="cantidad" id="cantidad" value = "<?php //print $view->parte->getHs100() ?>" >
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    </div>
                </div>



                <div class="form-group col-md-2">
                    <!--<label for="search">&nbsp;</label>-->
                    <button type="submit" class="form-control btn btn-default" title="nuevo parte" id="new" <?php echo ( PrivilegedUser::dhasAction('PAR_INSERT', array(1)) )? '' : 'disabled' ?>>
                        <i class="fas fa-plus dp_green"></i>
                    </button>
                </div>


            </div>



            <div class="table-responsive fixedTable" id="conceptos-container">

                <table class="table table-condensed dataTable table-hover" id="conceptos-table">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


            </div>


            <?php //include_once('view/novedades2/conceptosGrid.php');?>




            <hr/>



            <div class="form-group">
                <!--<label class="control-label" for="servicio">Comentario</label>-->
                <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario" rows="2"><?php //print $view->empleado->getComentario(); ?></textarea>
            </div>



            <div id="myElem" class="msg" style="display:none"></div>


            <div id="footer-buttons" class="pull-right">
                <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
                <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
                <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
            </div>



        </form>


    </div>


    <div class="col-md-6" id="right_side">


        <!-- seccion de ordenes -->
        <div class="row">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>
            </div>

            <div class="col-md-4">

            </div>

            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-block" id="add-orden" name="add-orden" title="Agregar orden" <?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?> >
                    <i class="fas fa-plus dp_green"></i>&nbsp
                </button>
            </div>
        </div>


        <div id="demo-ordenes" class="collapse">
            <div class="grid-ordenes">
                <?php include_once('view/novedades_partes/ordenesGrid.php');?>
            </div>
        </div>

        <br/>

        <!-- seccion de sucesos -->
        <div class="row">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-sucesos" title="Mostrar sucesos">Sucesos</button>
            </div>

            <div class="col-md-4">

            </div>

            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-block" id="add-suceso" name="add-suceso" title="Agregar suceso" <?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?> >
                    <i class="fas fa-plus dp_green"></i>&nbsp
                </button>
            </div>
        </div>


        <div id="demo-sucesos" class="collapse">
            <div class="grid-sucesos">
                <?php include_once('view/novedades2/sucesosGrid.php');?>
            </div>
        </div>


    </div>

</div>




<div id="confirm-ord">
    <div class="modal-body">
        ¿Desea eliminar la orden?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>



<div id="confirm-suc">
    <div class="modal-body">
        ¿Desea eliminar el suceso?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>













