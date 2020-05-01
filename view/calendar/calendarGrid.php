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
                        operation: 'get'
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
                                color: 'gray' // override!
                            });
                        });

                        //sucesos
                        //alert(Object.keys(data['sucesos']).length);
                        $(data['sucesos']).each(function(index) {
                            events.push({
                                title: data['sucesos'][index].empleado+' '+data['sucesos'][index].evento,
                                start: data['sucesos'][index].fecha_desde,
                                end: data['sucesos'][index].fecha_hasta+' 23:59:00',
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
            return 'es un feriado';
        }
        else if(info.event.extendedProps.tipo_evento == 'suceso') {
            let s = new Date(info.event.start).toLocaleDateString('en-GB'); //formato dd/mm/yyyy
            let e = new Date(info.event.end).toLocaleDateString('en-GB');
            return e;
        }
    }



    $(document).ready(function(){

    });

</script>


<br/>
<div class="col-md-3">

    panel izquierdo
</div>

<div class="col-md-9">

    <div id='calendar'></div>

</div>






    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

<div id="myElemento" class="msg" style="display:none">

</div>





