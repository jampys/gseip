<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid' ],
            locale: 'es',//lang: 'es'

            events: function(info, callback, fail) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        action: 'nov_calendar',
                        operation: 'get'
                        //start: start.format(),
                        //end: end.format()
                    },
                    success: function(doc) {
                        var events = [];
                        //$(doc).find('event').each(function() {
                        $(doc).each(function() {
                            events.push({
                                title: 'culo', //$(this).attr('title'),
                                start: '2020-05-01',//$(this).attr('start') // will be parsed
                                end: '2020-05-01'
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





