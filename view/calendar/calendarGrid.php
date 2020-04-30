<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid' ],
            locale: 'es'//lang: 'es'
        });

        calendar.render();
    });



    $(document).ready(function(){







    });

</script>

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





