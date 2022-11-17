﻿

<script type="text/javascript">


    $(document).ready(function(){

        var table = $('#example').DataTable({
            responsive: true,
            deferRender: true,
            //processing: true,
            language: {
                //url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            'ajax': {
                "type"   : "POST",
                //"url"    : 'index.php?action=ajax_certificados&operation=refreshGrid',
                "url"    : 'index.php',
                //data: {action: "ajax_certificados", operation:"refreshGrid"},
                "data": function ( d ) { //https://datatables.net/reference/option/ajax.data
                    d.id_categoria = $("#id_categoria").val();
                    d.mes_programada = $("#mes_programada").val();
                    d.asistio = $("#asistio").val();
                    d.id_empleado = $("#id_empleado").val();
                    d.id_contrato = ($("#id_contrato").val()!= null)? $("#id_contrato").val() : '';
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.action = "cap_capacitaciones_hist";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            //rowId: 'id_objetivo',
            'columns': [
                /*{   // Responsive control column
                 data: null,
                 defaultContent: '',
                 className: 'control',
                 orderable: false
                 },*/
                {"data" : "fecha_edicion"},
                {"data" : "empleado"},
                {"data" : "categoria"},
                {"data" : "tema"},
                {"data" : "modalidad"},
                {"data" : "capacitador"},
                {"data" : ""}, //asistió
                {"data" : "duracion"},
                {"data" : null, orderable: false}
            ],
            "order": [[ 0, 'desc' ], [ 1, 'desc' ]], //fecha, empleado
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_capacitacion);
            },
            "columnDefs": [
                {targets: 0, type: 'date-uk', orderData: [ 0, 1]}, //fecha
                {
                    targets: 6,//asistió
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        return (row.asistio == 1)? '<span class="dp_green">SI</span>' : '<span class="dp_red">NO</span>';
                    }
                },
                {
                    targets: 8,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let user_info = row.user.split('@')[0]+' '+row.created_date;
                        return  '<a href="#" title="'+user_info+'" onclick="return false;">'+
                                    '<i class="fa fa-question-circle dp_light_gray"></i>'+
                                '</a>';
                    }
                }
            ],
            "footerCallback": function ( row, data, start, end, display ) { //https://datatables.net/examples/advanced_init/footer_callback.html
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total participantes over all pages
                totalP = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total participantes over this page
                pageTotalP = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 7 ).footer() ).html(pageTotalP +' ('+ totalP +' total)');
            }

        });



    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Empleado</th>
                <th>Tipo cap.</th>
                <th>Tema</th>
                <th>Modalidad</th>
                <th>Capacitador</th>
                <th>Asistió</th>
                <th>Duración [hs]</th>
                <th></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->

