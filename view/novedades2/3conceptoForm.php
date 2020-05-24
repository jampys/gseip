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

        //$('.collapse').collapse(); //https://bootstrapdocs.com/v3.3.4/docs/javascript/#collapse

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });

        $('#confirm').dialog({ //#confirm-emp, #confirm-ord, #confirm-con
            autoOpen: false
            //modal: true,
        });


        //datepicker repetir
        $('.input-group.date.rf').datepicker({
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        //restringe el selector de fechas al periodo seleccionado
        var fecha_desde = $('#add_fecha').val();
        var fecha_hasta = $('#fecha_hasta').val();
        $('.input-group.date.rf').datepicker('setStartDate', fecha_desde);
        $('.input-group.date.rf').datepicker('setEndDate', fecha_hasta);



        $('#empleado-form').on('click', '#cancel', function(){
            $('#contenedor').hide();
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
            $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
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

                ],
                open: function() {
                    $(this).html(confirmMessage('¿Desea eliminar la orden?'));
                }
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
                    $("#confirm #myElemento").html('Orden eliminada con exito').addClass('alert alert-success').show();
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm #myElemento").hide();
                                            //$('#orden-form').hide();
                                            $('.grid-ordenes').load('index.php',{action:"parte-orden", operation: "refreshGrid", id_parte: params.id_parte}); //para la modal (nov2)
                                            $('#confirm').dialog('close');
                                            $('#table_empleados').load('index.php',{action:"novedades2", operation:"tableEmpleados", fecha: $('#add_fecha').val(), id_contrato: $('#id_contrato').val()});
                                          }, 2000);
                }else{
                    $("#confirm #myElemento").html('Error al eliminar la orden').addClass('alert alert-danger').show();
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
                $('#myModal #id_empleado').prop('disabled', true).selectpicker('refresh');
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
                //$('#myModal #id_empleado').val(params.id_empleado).trigger('change').prop('disabled', true);
                //$('.selectpicker').selectpicker('refresh');
                //$('#myModal #id_empleado').selectpicker('val', params.id_empleado).prop('disabled', true); //https://developer.snapappointments.com/bootstrap-select/methods/
                $('#myModal').modal();
            })
        });



        /*$(document).on('click', '#cancel',function(){
            $('#myModal').modal('hide');
        });*/



        //eliminar un suceso
        $('.grid-sucesos').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarS(id);
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
                    $(this).html(confirmMessage('¿Desea eliminar el suceso?'));
                }
            }).dialog('open');
            return false;
        });


        $.fn.borrarS = function(id) { //ok
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
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    setTimeout(function() { $("#confirm #myElemento").hide();
                                            //$('#orden-form').hide();
                                            $('.grid-sucesos').load('index.php',{action:"novedades2", operation: "sucesosRefreshGrid", id_empleado: $('#id_empleado').val(), id_contrato: $('#id_contrato').val(), id_periodo: $('#id_periodo').val()}); //para la modal (nov2)
                                            $('#confirm').dialog('close');
                                            $('#table_empleados').load('index.php',{action:"novedades2", operation:"tableEmpleados", fecha: $('#add_fecha').val(), id_contrato: $('#id_contrato').val()});
                                         }, 2000);
                    }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                $("#myElemento").html('No es posible eliminar el suceso').addClass('alert alert-danger').show();
            });

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
            snapToStep: true,
            //defaultTime: false
            defaultTime: '00:00 AM'
        });




        $.cargarTablaConceptos = function(){

            var counter = 0; //para contar los elementos

            $('#conceptos-table tbody tr').remove(); //elimina los elementos de la tabla de conceptos
            $('#conceptos-container .alert').remove(); //elimina el mensaje de alerta

            for (var i in jsonConceptos) {

                if (jsonConceptos[i].operacion == 'delete') { //para no mostrar los eliminados
                    continue;
                }
                counter ++;

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
                    showMeridian: false,
                    snapToStep: true
                    //defaultTime: false
                }).on('changeTime.timepicker', function(e) {
                    var id = $(this).closest('tr').attr('id_parte_empleado_concepto');
                    jsonConceptos[id].cantidad = e.time.value;
                    if (jsonConceptos[id].id_parte_empleado) jsonConceptos[id].operacion = 'update';
                    //alert(jsonConceptos[id].operacion);

                }).timepicker('setTime', jsonConceptos[i].cantidad);


            }

            //if(Object.keys(jsonConceptos).length <= 0){
            //alert(counter);
            if(counter <= 0){

                $('#conceptos-container').append('<div class="alert alert-warning">'+
                '<i class="fas fa-exclamation-triangle fa-fw"></i> El parte aún tiene conceptos registrados.'+
                '</div>');

                //return; //finaliza la ejecucion de la funcion.
            }

        };



        //carga los conceptos cuando se selecciona un empleado
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




        //al seleccionar (cambiar) una ruta
        $('#empleado-form').on('change', '#id_ruta', function(){

            var id_ruta = $(this).val();
            //alert(id_ruta);
            //return false;

            $.ajax({
                url:"index.php",
                type:"post",
                data:{"action": "novedades2", "operation": "loadConceptosRutas", "id_ruta": id_ruta},
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //Si la ruta no tiene conceptos. Sale
                    if(data.length <= 0 ){
                        alert('la ruta no tiene definidos los conceptos');
                        return false;
                    }

                    //eliminar los elementos de jsonConceptos
                    for (var i in jsonConceptos) {

                        if (jsonConceptos[i].id_parte_empleado_concepto >= 0 ) { //si esta en la BD
                            jsonConceptos[i].operacion = 'delete';
                        }else{
                            delete jsonConceptos[i];
                        }
                    }


                    var ix = -1;
                    $.each(data, function(indice, val){ //carga el array de empleados

                        item = {};
                        item.operacion = 'insert';
                        //item.id_parte_empleado_concepto = id;
                        item.id_parte_empleado_concepto = ix;
                        item.id_parte_empleado = null; //$("#id_empleado option:selected").text();
                        item.id_concepto_convenio_contrato = data[indice]['id_concepto_convenio_contrato'];
                        item.convenio = data[indice]['convenio'];
                        item.concepto = data[indice]['concepto'];
                        item.codigo = data[indice]['codigo'];
                        item.cantidad = data[indice]['cantidad'];
                        item.created_by = null;
                        item.created_date = null;
                        item.tipo_calculo = 'M';
                        item.motivo = null;
                        jsonConceptos[ix] = item;
                        ix--;

                    });

                    $.cargarTablaConceptos();


                }

            });

            return false;

        });




        //al seleccionar dia anterior
        $('#empleado-form').on('click', '#dia_anterior', function(){ //ok

            $.ajax({
                url:"index.php",
                type:"post",
                data:{action: "novedades2", operation: "loadDiaAnterior", id_empleado: $('#id_empleado').val(), id_contrato: $('#id_contrato').val(), fecha_parte: $('#add_fecha').val()},
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    $('#id_cuadrilla').val(data['parte'][0]['id_cuadrilla']);
                    $('#id_area').val(data['parte'][0]['id_area']);
                    $('#id_evento').val(data['parte'][0]['id_evento']);
                    $("#conductor").prop('checked', (data['parte'][0]['conductor']==1)? true:false);
                    $('.selectpicker').selectpicker('refresh');


                    //eliminar los elementos de jsonConceptos
                    for (var i in jsonConceptos) {

                        if (jsonConceptos[i].id_parte_empleado_concepto >= 0 ) { //si esta en la BD
                            jsonConceptos[i].operacion = 'delete';
                        }else{
                            delete jsonConceptos[i];
                        }
                    }


                    var ix = -1;
                    $.each(data['conceptos'], function(indice, val){ //carga el array de empleados

                        item = {};
                        item.operacion = 'insert';
                        //item.id_parte_empleado_concepto = id;
                        item.id_parte_empleado_concepto = ix;
                        item.id_parte_empleado = null; //$("#id_empleado option:selected").text();
                        item.id_concepto_convenio_contrato = data['conceptos'][indice]['id_concepto_convenio_contrato'];
                        item.convenio = data['conceptos'][indice]['convenio'];
                        item.concepto = data['conceptos'][indice]['concepto'];
                        item.codigo = data['conceptos'][indice]['codigo'];
                        item.cantidad = data['conceptos'][indice]['cantidad'];
                        item.created_by = null;
                        item.created_date = null;
                        item.tipo_calculo = 'M';
                        item.motivo = null;
                        jsonConceptos[ix] = item;
                        ix--;

                    });

                    $.cargarTablaConceptos();


                }

            });

            return false;

        });





        //al presionar el boton para agregar conceptos
        $('#left_side').on('click', '#new', function(){ //ok

            //id_concepto_convenio_contrato
            if(!$('#id_concepto_convenio_contrato').val()) return false; //para evitar agregar conceptos en blanco.

            //var first_key = Object.keys(jsonConceptos)[0];
            var id = '';
            if(Object.keys(jsonConceptos).length == 0){
                id = -1;
            }
            else{
                var last_key = Object.keys(jsonConceptos).length - 1;
                var last = Object.keys(jsonConceptos)[last_key];
                if (last > 0 ) id = -1;
                else id = last - 1;
            }

            item = {};
            item.operacion = 'insert';
            item.id_parte_empleado_concepto = id;
            item.id_parte_empleado = null; //$("#id_empleado option:selected").text();
            item.id_concepto_convenio_contrato = $("#id_concepto_convenio_contrato").val();
            item.convenio = $("#id_concepto_convenio_contrato option:selected").attr('convenio');
            item.concepto = $("#id_concepto_convenio_contrato option:selected").attr('concepto');
            item.codigo = $("#id_concepto_convenio_contrato option:selected").attr('codigo');
            item.cantidad = $("#cantidad").val();
            item.created_by = null;
            item.created_date = null;
            item.tipo_calculo = 'M';
            item.motivo = null;
            //alert('id asignado: '+item.id_parte_empleado_concepto);
            jsonConceptos[id] = item; //se agrega el item al final del array asociativo

            $.cargarTablaConceptos();

            return false;

        });



        //Elimina un concepto de la tabla
        $('#conceptos-table').on('click', '.delete', function(e){ //ok
            //alert('eliminar concepto');
            var id = $(this).closest('tr').attr('id_parte_empleado_concepto');
            //alert('id eliminado: '+jsonConceptos[id].id_parte_empleado_concepto);
            //throw  new Error();
            if(jsonConceptos[id].id_parte_empleado_concepto < 0 ){ //si no esta en la BD
                delete jsonConceptos[id]; //lo elimina del array
            }else{ //si esta en la BD, lo marca para eliminar
                jsonConceptos[id].operacion = 'delete';
            }

            $.cargarTablaConceptos();
            return false;
        });






        $('#empleado-form').on('click', '#submit',function(e){
            e.preventDefault();
            //alert('guardar conceptos');
            //if ($("#contrato-form").valid()){
                var params={};
                params.action = 'novedades2';
                params.operation = 'saveParteR';
                params.id_parte = $('#id_parte').val();
                params.fecha_parte = $('#add_fecha').val();
                params.id_contrato=$('#id_contrato').val();
                params.id_cuadrilla = $('#id_cuadrilla').val();
                params.id_area = $('#id_area').val();
                params.id_periodo = $('#id_periodo').val();
                params.comentario = $('#comentario').val();

                params.id_parte_empleado = $('#id_parte_empleado').val();
                params.id_empleado = $('#id_empleado').val();
                params.id_evento = $('#id_evento').val();
                params.conductor = $('#conductor').prop('checked')? 1:0;
                params.check_replicar = ($('#check_replicar').is(':checked'))? 1:0;
                //params.check_sobrescribir = ($('#check_sobrescribir').is(':checked'))? 1:0;
                params.rep_fecha = $('#rep_fecha').val();
                //alert(params.comentario);
                //throw new Error();

                var jsonConceptosIx = [];
                for ( var item in jsonConceptos ){
                    jsonConceptosIx.push( jsonConceptos[ item ] );
                }
                params.vConceptos = JSON.stringify(jsonConceptosIx);


                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado viene de una transaccion (try catch) que siempre devuelve 1 o -1
                    //alert(xhr.responseText);
                    //alert(data[0]['flag']);
                    if(data[0]['flag'] >=0){
                        $("#empleado-form button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Parte guardado con exito').addClass('alert alert-success').show();
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#table_empleados').load('index.php',{action:"novedades2", operation:"tableEmpleados"});
                                                $("#add_fecha").trigger("changeDate");
                                                $("#contenedor").hide("");
                                              }, 1000);

                    }else{
                        $("#myElem").html(data[0]['msg']).addClass('alert alert-danger').show();
                    }
                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                        alert('Entro a fail '+jqXHR.responseText);
                        //$("#myElem").html('Error al guardar la búsqueda').addClass('alert alert-danger').show();
                });

            //}
            return false;
        });


        //Eliminar parte
        $('#empleado-form').on('click', '#delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            //var id = $(this).closest('tr').attr('data-id');
            var id = $('#empleado-form #id_parte').val();
            $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarP(id);
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
                    $(this).html(confirmMessage('¿Desea eliminar el parte? '+
                    'Se elimiminará el parte completo, incluyendo empleados, conceptos y ordenes.'));
                }
            }).dialog('open');
            return false;
        });


        $.fn.borrarP = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_parte = id;
            params.action = "partes";
            params.operation = "deleteParte";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    $("#myElemento").html('Parte eliminado con exito').addClass('alert alert-success').show();
                    //$('#content').load('index.php',{action:"partes", operation: "refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    setTimeout(function() { $("#myElemento").hide();
                                            $('#confirm').dialog('close');
                                            $("#add_fecha").trigger("changeDate");
                                          }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                $("#myElemento").html('No es posible eliminar el parte').addClass('alert alert-danger').show();
            });


        };







    });

</script>

<div class="row" id="contenedor">

    <div class="col-md-6" id="left_side">


        <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">




            <div class="alert alert-info">
                <strong><?php echo $view->label; ?></strong>
                <a id="dia_anterior" class="pull-right" href="#" title="día anterior"><i class="fas fa-clone fa-fw"></i></a>
            </div>


            <input type="hidden" name="id_parte" id="id_parte" value="<?php print $view->parte->getIdParte() ?>">
            <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php print $view->params['id_parte_empleado'] ?>">
            <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->params['id_contrato'] ?>">
            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->empleado->getIdEmpleado() ?>">



            <div class="form-group">
                <!--<label class="control-label" for="id_empleado">Cuadrilla</label>-->
                <select id="id_cuadrilla" name="id_cuadrilla" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                    <option value="">Seleccione una cuadrilla</option>
                    <?php foreach ($view->cuadrillas as $cu){
                        ?>
                        <option value="<?php echo $cu['id_cuadrilla']; ?>"
                            <?php //echo ($cu['id_cuadrilla'] == $view->parte->getIdCuadrilla())? 'selected' :'' ?>
                            <?php echo ( ($cu['id_cuadrilla'] == $view->parte->getIdCuadrilla()) || (!$view->parte->getIdParte() && $cu['id_cuadrilla'] == $view->defaults[0]['id_cuadrilla'])  )? 'selected' :'' ?>
                            >
                            <?php echo $cu['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>

            <div class="form-group">
                <!--<label for="default_id_area" class="control-label">Área</label>-->
                <select class="form-control selectpicker show-tick" id="id_area" name="id_area" data-live-search="true" data-size="5">
                    <option value="">Seleccione un área</option>
                    <?php foreach ($view->areas as $ar){
                        ?>
                        <option value="<?php echo $ar['id_area']; ?>"
                            <?php //echo ($ar['id_area'] == $view->parte->getIdArea())? 'selected' :'' ?>
                            <?php echo ( ($ar['id_area'] == $view->parte->getIdArea()) || (!$view->parte->getIdParte() && $ar['id_area'] == $view->defaults[0]['default_id_area'])  )? 'selected' :'' ?>
                            >
                            <?php echo $ar['codigo']." ".$ar['nombre'];?>
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
                            <?php echo ($ar['enabled'])? '':'disabled'; ?>
                            <?php echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                            >
                            <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>


            <div class="form-group required">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="conductor" name="conductor" <?php //echo ($view->parte_empleado->getConductor()== 1)? 'checked' :'' ?>
                                                                               <?php echo ( ($view->parte_empleado->getConductor()== 1) || (!$view->parte->getIdParte() && 1 == $view->defaults[0]['conductor'])  )? 'checked' :'' ?>
                            ><a href="#" title="Marcar la persona que maneja">Conductor</a>
                    </label>
                </div>
            </div>

            <hr/>
            <!-- SECCION DE CONCEPTOS -->

            <div class="form-group">
                <label for="id_evento" class="control-label">Conceptos</label>
                <select class="selectpicker form-control show-tick" id="id_ruta" name="id_ruta" data-live-search="true" data-size="5" title="Rutas predefinidas">
                    <!--<option value="">Seleccione una ruta</option>-->
                    <?php foreach ($view->rutas as $ru){ ?>
                        <option value="<?php echo $ru['id_ruta']; ?>"
                            <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                            >
                            <?php echo $ru['nombre']; ?>
                        </option>
                    <?php  } ?>
                </select>
            </div>



            <div class="row">


                <div class="form-group col-md-6">
                    <!-- <label for="add_contrato" class="control-label">Nuevos partes</label>-->
                    <select class="form-control selectpicker show-tick" id="id_concepto_convenio_contrato" name="id_concepto_convenio_contrato" data-live-search="true" data-size="5" title="Seleccione un concepto">
                        <!--<option value="">Seleccione un concepto</option>-->
                        <?php foreach ($view->conceptos as $con){
                            ?>
                            <option value="<?php echo $con['id_concepto_convenio_contrato']; ?>"
                                    convenio = "<?php echo $con['convenio']; ?>"
                                    concepto = "<?php echo $con['concepto']; ?>"
                                    codigo = "<?php echo $con['codigo']; ?>"
                                >
                                <?php echo $con['concepto'].' ('.$con['codigo'].') '.$con['convenio'];?>
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
                    <button type="submit" class="form-control btn btn-default" title="agregar concepto" id="new" <?php echo ( PrivilegedUser::dhasAction('PAR_INSERT', array(1)) )? '' : 'disabled' ?>>
                        <i class="fas fa-arrow-down dp_green"></i>
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
                <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario" rows="2"><?php print $view->parte_empleado->getComentario(); ?></textarea>
            </div>


            <div class="row">
                <div class="form-group col-md-6">
                    <div class="checkbox">
                        <label><input type="checkbox" id="check_replicar" value="">
                            <span title="Marcar para replicar la novedad hasta la fecha indicada inclusive. Solo se replica durante días habiles que no tengan una novedad previa.">
                                Replicar novedad
                            </span></label>
                    </div>
                </div>
                <!--<div class="form-group col-md-3">
                    <div class="checkbox">
                        <label><input type="checkbox" id="check_sobrescribir" title="Marcar para sobrescribir novedades" value="">Sobrescribir</label>
                    </div>
                </div>-->
                <div class="form-group col-md-6">
                    <div class="form-group">
                        <div class="input-group date rf">
                            <input class="form-control" type="text" name="rep_fecha" id="rep_fecha" value = "<?php print $view->empleado->getFechaBaja() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div id="myElem" class="msg" style="display:none"></div>


            <!-- boton para eliminar el parte -->
            <?php if($view->parte->getIdParte()){ ?>
            <div class="pull-left">
                <button class="btn btn-danger" id="delete" name="delete" type="submit" title="Eliminar parte"
                    <?php print (
                                    !$view->periodo->getClosedDate() &&
                                    ((PrivilegedUser::dhasAction('PAR_DELETE', array(1)) && $view->parte->getCreatedBy() == $_SESSION['id_user'])
                                        ||
                                        (PrivilegedUser::dhasPrivilege('USR_ABM', array(0))) //solo el administrador
                                    )


                                )? '':'disabled';
                                ?>><i class="far fa-trash-alt fa-lg" aria-hidden="true"></i></button>
            </div>
            <?php } ?>

            <div id="footer-buttons" class="pull-right">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php print ($view->periodo->getClosedDate())? 'disabled':''; ?> >Guardar</button>
                <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
                <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
            </div>



        </form>


    </div>


    <div class="col-md-6" id="right_side">


        <!-- seccion de ordenes -->
        <div class="row">
            <div class="col-md-4">
                <!--<button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>-->
                <button type="button" class="btn btn-primary btn-block" title="Órdenes del parte">Órdenes</button>
            </div>

            <div class="col-md-4">

            </div>

            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-block" id="add-orden" name="add-orden" title="Agregar orden" <?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' && $view->parte->getIdParte() )? '' : 'disabled' ?> >
                    <i class="fas fa-plus dp_green"></i>&nbsp
                </button>
            </div>
        </div>


        <!--<div id="demo-ordenes" class="collapse">-->
        <div id="demo-ordenes">
            <div class="grid-ordenes">
                <?php include_once('view/novedades_partes/ordenesGrid.php');?>
            </div>
        </div>

        <br/>

        <!-- seccion de sucesos -->
        <div class="row">
            <div class="col-md-4">
                <!--<button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-sucesos" title="Mostrar sucesos">Sucesos</button>-->
                <button type="button" class="btn btn-primary btn-block" title="Sucesos del período">Sucesos</button>
            </div>

            <div class="col-md-4">

            </div>

            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-block" id="add-suceso" name="add-suceso" title="Agregar suceso" <?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?> >
                    <i class="fas fa-plus dp_green"></i>&nbsp
                </button>
            </div>
        </div>


        <!--<div id="demo-sucesos" class="collapse">-->
        <div id="demo-sucesos">
            <div class="grid-sucesos">
                <?php include_once('view/novedades2/sucesosGrid.php');?>
            </div>
        </div>


    </div>

</div>




<div id="confirm">

</div>













