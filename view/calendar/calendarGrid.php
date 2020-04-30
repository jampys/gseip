<script type="text/javascript">


    $(document).ready(function(){



        $('#confirm').dialog({
            autoOpen: false,
            //modal: true,
            buttons: [
                        {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar($('#confirm').data('id'));
                        },
                        class:"ui-button-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"ui-button-danger"
                    }

                    ]
        });


    });

</script>

<div class="col-md-1"></div>

<div class="col-md-10">

   HAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA

</div>

<div class="col-md-1"></div>




    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

<div id="myElemento" class="msg" style="display:none">

</div>





