<script type="text/javascript">

    $(document).ready(function(){

   // ***********fullcalendar *************************************************//

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',//lang: 'es'
            plugins: [ 'interaction', 'dayGrid' ],
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            defaultView: 'dayGridMonth',
            //defaultDate: '2020-02-12',
            displayEventTime: false,
            businessHours: true, // sabado y domingo con background gris
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            eventRender: function(info) { //https://fullcalendar.io/docs/eventRender
                var pop = $(info.el).popover({
                    title: info.event.title,
                    placement:'top',
                    trigger : 'hover',
                    html:true,
                    //content: format(info),
                    container:'body'
                }).popover('show');
                pop.attr("data-content", format(info));
            },

            events: function(info, successCallback, failureCallback) { //https://fullcalendar.io/docs/events-function

                //alert($('#check_concepto').is(':checked'));
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        start: info.start.valueOf(),
                        end: info.end.valueOf(),
                        action: 'nov_calendar',
                        operation: 'get',
                        radio_vista: $("input[name='radio_vista']:checked").val(),
                        check_concepto: ($('#check_concepto').is(':checked'))? 1:0,
                        check_suceso: ($('#check_suceso').is(':checked'))? 1:0,
                        id_contrato : $('#id_contrato').val(),
                        eventos : ($("#id_evento").val()!= null)? $("#id_evento").val() : '',
                        empleados : ($("#id_empleado").val()!= null)? $("#id_empleado").val() : '',
                        sucesos : ($("#id_suceso").val()!= null)? $("#id_suceso").val() : ''
                    },
                    success: function(data) {
                        var events = [];

                        //feriados
                        $(data['feriados']).each(function(index) {
                            events.push({
                                title: data['feriados'][index].title,
                                start: data['feriados'][index].start,
                                end: data['feriados'][index].end,
                                extendedProps: {
                                    tipo_evento: data['feriados'][index].tipo_evento,
                                    details: data['feriados'][index].details
                                },
                                color: 'gray' // #ff9f89
                                //rendering: 'background'
                            });
                        });

                        //sucesos
                        //alert(Object.keys(data['sucesos']).length);
                        $(data['sucesos']).each(function(index) {
                            events.push({
                                title: data['sucesos'][index].empleado+' '+data['sucesos'][index].evento,
                                start: data['sucesos'][index].fecha_desde+'T00:00:00',
                                end: data['sucesos'][index].fecha_hasta+'T23:59:00',
                                color: '#ff9900', // override!
                                //textColor: 'gray',
                                extendedProps: {
                                    tipo_evento: data['sucesos'][index].tipo_evento
                                    //details: data['sucesos'][index].details
                                }
                            });
                        });

                        //novedades empleado
                        //alert(Object.keys(data['sucesos']).length);
                        $(data['novedades_empleado']).each(function(index) {
                            events.push({
                                title: data['novedades_empleado'][index].empleado,
                                start: data['novedades_empleado'][index].fecha_parte+'T00:00:00',
                                end: data['novedades_empleado'][index].fecha_parte+'T23:59:00',
                                color: (data['novedades_empleado'][index].id_evento)? 'tomato':'',
                                //textColor: 'gray',
                                extendedProps: {
                                    tipo_evento: data['novedades_empleado'][index].tipo_evento,
                                    legajo: data['novedades_empleado'][index].legajo,
                                    cuadrilla: data['novedades_empleado'][index].cuadrilla,
                                    area: data['novedades_empleado'][index].area,
                                    evento: data['novedades_empleado'][index].evento,
                                    conceptos: data['novedades_empleado'][index].conceptos
                                }
                            });
                        });

                        successCallback(events);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    alert(jqXHR.responseText);
                });
            }




        });

        calendar.render();



// ********funcion que formatea el detalle de los eventos que se muestran en un popover *************/

    function format(info){
        let msg = '';
        if(info.event.extendedProps.tipo_evento == 'feriado') {
            return '<span>'+info.event.extendedProps.details+'</span>';
        }
        else if(info.event.extendedProps.tipo_evento == 'suceso') {
            let s = new Date(info.event.start).toLocaleDateString('en-GB'); //formato dd/mm/yyyy
            let e = new Date(info.event.end).toLocaleDateString('en-GB');
            msg = '<span>Desde: '+s+'</span><br/>';
            msg += '<span>Hasta: '+e+'</span>';
            return msg;
        }
        else if(info.event.extendedProps.tipo_evento == 'novedad_empleado') {
            msg = (info.event.extendedProps.cuadrilla)? '<span>'+info.event.extendedProps.cuadrilla+'</span><br/>' : '';
            msg += (info.event.extendedProps.area)? '<span>'+info.event.extendedProps.area+'</span><br/>' : '';
            msg += (info.event.extendedProps.evento)? '<span>'+info.event.extendedProps.evento+'</span><br/>' : '';
            msg += (info.event.extendedProps.conceptos)? '<span>'+info.event.extendedProps.conceptos+'</span>' : '';
            return msg;
        }
    }



// *********evento al cambiar los filtros de busqueda ********************/

        $('#id_suceso, #id_evento, #id_contrato, #id_empleado, #check_suceso, #check_concepto, #id_concepto, #id_cuadrilla').on('change', function(){
            //alert('apapapaapapa');
            calendar.refetchEvents();

        });



 // *************** llamadas asincronicas para poblar los filtros de busqueda ************/

        function getData(url, params){
            var jqxhr = $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json"//xml,html,script,json
            });
            return jqxhr ;
        }


        $(document).on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "nov_calendar";
            params.operation = "getEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();

            getData('index.php', params)
                .then(function(data){

                    //completo select de empleados
                    $('#id_empleado').empty();
                    if(Object.keys(data).length > 0){
                        //$('#id_empleado').html('<option value="">Todos los empleados</option>');
                        $.each(data, function(index, val){
                            var label = data[index]["legajo"]+' '+data[index]["apellido"]+' '+data[index]["nombre"];
                            $("#id_empleado").append('<option value="'+data[index]["id_empleado"]+'"'
                            +' id_convenio="'+data[index]["id_convenio"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#id_empleado').selectpicker('refresh');
                    }

                    params.operation = "getCuadrillas";
                    return getData('index.php', params);


                }).then(function(data){

                    //completo select de cuadrillas
                    $('#id_cuadrilla').empty();
                    if(Object.keys(data).length > 0){
                        //$('#id_cuadrilla').html('<option value="">Todas las cuadrillas</option>');
                        $.each(data, function(index, val){
                            var label = data[index]["nombre"];
                            $("#id_cuadrilla").append('<option value="'+data[index]["id_cuadrilla"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#id_cuadrilla').selectpicker('refresh');
                    }

                    params.action = "parte-empleado-concepto";
                    params.operation = "getConceptos";
                    return getData('index.php', params);

                })
                .then(function(data){

                    //completo select de conceptos
                    $('#id_concepto').empty();
                    if(Object.keys(data).length > 0){
                        $.each(data, function(indice, val){
                            var label = data[indice]["concepto"]+' ('+data[indice]["codigo"]+') '+data[indice]["convenio"];
                            $("#id_concepto").append('<option value="'+data[indice]["id_concepto_convenio_contrato"]+'">'+label+'</option>');

                        });
                        $('#id_concepto').selectpicker('refresh');
                    }

                })
                .catch(function(data, textStatus, errorThrown){

                    alert(data.responseText);
                })


        });



        //Select dependiente: al seleccionar emppleado carga conceptos
        /*$(document).on('change', '#id_empleado', function(e){ //ok

            params={};
            params.action = "parte-empleado-concepto";
            params.operation = "getConceptos";
            params.id_convenio = $('#id_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();


            getData('index.php', params)
                .then(function(data){

                    //completo select de conceptos
                    $('#id_concepto').empty();
                    if(Object.keys(data).length > 0){
                        $.each(data, function(indice, val){
                            var label = data[indice]["concepto"]+' ('+data[indice]["codigo"]+') '+data[indice]["convenio"];
                            $("#id_concepto").append('<option value="'+data[indice]["id_concepto_convenio_contrato"]+'">'+label+'</option>');

                        });
                        $('#id_concepto').selectpicker('refresh');
                    }



                })
                .catch(function(data, textStatus, errorThrown){

                    alert(data.responseText);
                });


            });*/




        });








</script>


<br/>
<div class="col-md-3">

    <form name ="calendar-form" id="calendar-form" method="POST" action="index.php">

        <div class="alert alert-info fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <span class="glyphicon glyphicon-tags" ></span>&nbsp  Debe seleccionar un contrato para visualizar eventos en el calendario.
        </div>



        <div class="form-group">
            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" title="Seleccione un contrato" data-live-search="true" data-size="5">
                <?php foreach ($view->contratos as $con){
                    ?>
                    <option value="<?php echo $con['id_contrato']; ?>" >
                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>



        <div class="form-group">
            <!--<select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">-->
            <select multiple class="form-control selectpicker show-tick" id="id_evento" name="id_evento" title="Todos los eventos" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                <!--<option value="">Todos los eventos</option>-->
                <?php foreach ($view->eventos as $ar){ ?>
                    <option value="<?php echo $ar['id_evento']; ?>"
                        <?php //echo ($ar['enabled'])? '':'disabled'; ?>
                        <?php //echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                        >
                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>

        <hr/>

        <div class="radio">
            <label><input type="radio" name="radio_vista" value="empleado" checked><b>Vista empleado</b></label>
        </div>


        <div class="form-group">
            <!--<select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" data-live-search="true" data-size="5">-->
            <select multiple class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Todos los empleados" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                <!-- se completa dinamicamente desde javascript  -->
            </select>
        </div>


        <!--<div class="row">
            <div class="form-group col-md-2">
                <div class="checkbox">
                    <label><input type="checkbox" value=""></label>
                </div>
            </div>
            <div class="form-group col-md-10">
                <span class="form-control">Novedades</span>
            </div>
        </div>-->


        <div class="row">
            <div class="form-group col-md-2">
                <div class="checkbox">
                    <label><input type="checkbox" id="check_concepto" value="" checked></label>
                </div>
            </div>
            <div class="form-group col-md-10">
                <div class="form-group">
                    <!--<select class="form-control selectpicker show-tick" id="id_concepto" name="id_concepto" title="Seleccione un concepto" data-live-search="true" data-size="5">-->
                    <select multiple class="form-control selectpicker show-tick" id="id_concepto" name="id_concepto" title="Todos los conceptos" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                        <!-- se completa dinamicamente desde javascript  -->
                    </select>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="form-group col-md-2">
                <div class="checkbox">
                    <label><input type="checkbox" id="check_suceso" value="" checked></label>
                </div>
            </div>
            <div class="form-group col-md-10">
                <div class="form-group required">
                    <!--<select class="form-control selectpicker show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5" data-show-subtext="true">-->
                    <select multiple class="form-control selectpicker show-tick" id="id_suceso" name="id_suceso" title="Todos los sucesos" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                        <!--<option value="">Todos los sucesos</option>-->
                        <?php foreach ($view->sucesos as $ev){ ?>
                            <option value="<?php echo $ev['id_evento']; ?>" data-subtext="<?php echo $ev['tipo_liquidacion'] ;?>"
                                <?php //echo ($ev['id_evento'] == $view->suceso->getIdEvento())? 'selected' :'' ?>
                                >
                                <?php echo $ev['nombre'];?>
                            </option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
        </div>


        <div class="radio">
            <label><input type="radio" name="radio_vista" value="cuadrilla"><b>Vista cuadrilla</b></label>
        </div>


        <div class="form-group">
            <div class="form-group">
                <!--<select class="form-control selectpicker show-tick" id="id_cuadrilla" name="id_cuadrilla" title="Todas las cuadrillas" data-live-search="true" data-size="5">-->
                <select multiple class="form-control selectpicker show-tick" id="id_cuadrilla" name="id_cuadrilla" title="Todas las cuadrillas" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                    <!-- se completa dinamicamente desde javascript  -->
                </select>
            </div>
        </div>







    </form>


</div>

<div class="col-md-9">

    <div id='calendar'></div>

</div>






    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

<div id="myElemento" class="msg" style="display:none">

</div>





