<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[0, "desc"], [1, "asc"], [2, "asc"]],
            columnDefs: [
                {targets: [ 0 ], type: 'date-uk', orderData: [ 0, 1 ]},
                { responsivePriority: 1, targets: 8 },
                { responsivePriority: 2, targets: 6 }
            ]
        });*/

        $('#example').DataTable({
            responsive: true,
            language: {
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            "fnInitComplete": function () {
                $(this).show();
            },
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.search_fecha_desde = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.search_fecha_hasta = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.search_contrato = $("#add_contrato").val();
                    d.id_periodo = $("#id_periodo").val();
                    d.cuadrilla = $("#cuadrilla").val();
                    d.action = "partes";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[4, "desc"], [5, "desc"] ], //3=fecha_desde, 4=fecha_hasta
            'columns': [
                {"data" : "id_suceso"},
                {"data" : "evento"},
                {"data" : "empleado"},
                {"data" : "cantidad"},
                {"data" : "fecha_desde"},
                {"data" : "fecha_hasta"},
                {data: null, defaultContent: '', orderable: false},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_suceso);
            },
            "columnDefs": [
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4 ]}, //fecha_desde
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 4 ]}, //fecha_hasta
                {
                    targets: 6,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        return 1;
                    }
                }
            ]


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
                <th>IN</th>
                <th>Contrato</th>
                <th>Cuadrilla / Empleado</th>
                <th>Área</th>
                <th>Evento</th>
                <th></th>
                <th>Usuario</th>
                <th></th>
            </tr>
            </thead>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


