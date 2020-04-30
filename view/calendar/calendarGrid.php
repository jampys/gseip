<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid' ],
            locale: 'es',//lang: 'es'

            events: function(info, successCallback, failureCallback) {
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
                        //$(data).find('event').each(function() {
                        $(data).each(function() {
                            events.push({
                                title: 'culo', //$(this).attr('title'),
                                start: '2020-05-01',//$(this).attr('start') // will be parsed
                                end: '2020-05-01'
                            });
                        });
                        successCallback(events);
                    }
                });
            }




        });

        calendar.render();
    });



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





