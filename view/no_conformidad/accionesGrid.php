<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-vehiculos').DataTable({
            responsive: true,
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "nc_acciones";
                    d.operation = "refreshGrid";
                    d.id_no_conformidad = $('#etapas_left_side #add').attr('id_no_conformidad');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "accion"},
                {"data" : "user"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_accion);
            }
        });




    });

</script>


<?php if(isset($view->acciones) && sizeof($view->acciones) > 0) {?>
    
    <div id="empleados-table">
            <table id="table-vehiculos" class="table table-condensed dpTable table-hover dt-responsive nowrap">
                <thead>
                <tr>
                    <th>Acción</th>
                    <th>Usr.</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>




<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> La No conformidad no tiene acciones registradas.
    </div>

<?php } ?>





