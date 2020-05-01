<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function() {
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
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        start: info.start.valueOf(),
                        end: info.end.valueOf(),
                        action: 'nov_calendar',
                        operation: 'get',
                        id_contrato : $('#id_contrato').val()
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
                                color: '#ff9f89', // override!
                                rendering: 'background'
                            });
                        });

                        //sucesos
                        //alert(Object.keys(data['sucesos']).length);
                        $(data['sucesos']).each(function(index) {
                            events.push({
                                title: data['sucesos'][index].empleado+' '+data['sucesos'][index].evento,
                                start: data['sucesos'][index].fecha_desde+'T00:00:00',
                                end: data['sucesos'][index].fecha_hasta+'T23:59:00',
                                color: '#fdd835', // override!
                                textColor: 'black',
                                extendedProps: {
                                    tipo_evento: data['sucesos'][index].tipo_evento
                                    //details: data['sucesos'][index].details
                                }
                            });
                        });

                        //novedades

                        successCallback(events);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    alert(jqXHR.responseText);
                });
            }




        });

        calendar.render();
    });


    function format(info){
        //return '<p>'+info.event.extendedProps.details+'</p>'
        //+info.event.start
        if(info.event.extendedProps.tipo_evento == 'feriado') {
            return '<p>'+info.event.extendedProps.details+'</p>';
        }
        else if(info.event.extendedProps.tipo_evento == 'suceso') {
            let s = new Date(info.event.start).toLocaleDateString('en-GB'); //formato dd/mm/yyyy
            let e = new Date(info.event.end).toLocaleDateString('en-GB');
            return e;
        }
    }



    $(document).ready(function(){


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#myModal').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "partes2";
            params.operation = "getPeriodosAndEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();

            $('#myModal #id_empleado').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //completo select de empleados
                    if(Object.keys(data["empleados"]).length > 0){
                        $.each(data["empleados"], function(indice, val){
                            var label = data["empleados"][indice]["apellido"]+' '+data["empleados"][indice]["nombre"];
                            $("#myModal #id_empleado").append('<option value="'+data["empleados"][indice]["id_empleado"]+'"'
                            +' id_convenio="'+data["empleados"][indice]["id_convenio"]+'"'
                                //+' fecha_hasta="'+data["periodos"][indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#myModal #id_empleado').selectpicker('refresh');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });

    });

</script>


<br/>
<div class="col-md-3">

    <form name ="calendar-form" id="txt-form" method="POST" action="index.php">

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
            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" title="Seleccione un empleado" data-live-search="true" data-size="5">
                <!-- se completa dinamicamente desde javascript  -->
            </select>
        </div>


        <div class="row">
            <div class="form-group col-md-2">
                <div class="checkbox">
                    <label><input type="checkbox" value="" title="Marcar para mostrar enventos en el calendario" checked></label>
                </div>
            </div>
            <div class="form-group col-md-10">
                <select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">
                    <option value="">Todos los eventos</option>
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
        </div>


        <div class="radio">
            <label><input type="radio" name="optradio" checked><b>Vista empleado</b></label>
        </div>


        <div class="row">
            <div class="form-group col-md-2">
                <div class="checkbox">
                    <label><input type="checkbox" value=""></label>
                </div>
            </div>
            <div class="form-group col-md-10">
                <input type="text" class="form-control" name="meta_valor" id="meta_valor" value = "<?php //print $view->objetivo->getMetaValor() ?>" placeholder="Valor">
            </div>
        </div>


        <div class="radio">
            <label><input type="radio" name="optradio"><b>Vista cuadrilla</b></label>
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





