<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid' ],
            locale: 'es',//lang: 'es'
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: 'index.php',
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        action: 'nov_calendar',
                        operation: 'get',
                        start: start.unix(),
                        end: end.unix()
                    },
                    success: function(doc) {
                        var events = [];
                        $(doc).find('event').each(function() {
                            events.push({
                                title: $(this).attr('title'),
                                start: $(this).attr('start') // will be parsed
                            });
                        });
                        callback(events);
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





