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
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.search_contrato = $("#add_contrato").val();
                    d.id_periodo = $("#id_periodo").val();
                    d.cuadrilla = $("#cuadrilla").val();
                    d.action = "partes";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[0, "desc"], [1, "asc"], [2, "asc"]],
            'columns': [
                {"data" : "fecha_parte"},
                {"data" : "id_parte"},
                {"data" : "contrato"},
                {"data" : "cuadrilla"},
                {"data" : "area"},
                {"data" : "evento"},
                {data: null, defaultContent: '', orderable: false},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_parte);
            },
            "columnDefs": [
                {targets: 0, type: 'date-uk', orderData: [ 0, 1 ]}, //fecha_parte
                {
                    targets: 6,//botones indicadores
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let novedad = (row.id_parte)? '<i class="fas fa-car-side fa-fw dp_blue_nov" title="con novedad"></i>':'<i class="fas fa-car fa-fw dp_light_gray" title="sin novedad"></i>';
                        let conceptos = '';
                        let ordenes = '';
                        return '<a href="#">'+
                                    novedad+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+conceptos+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+ordenes+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>';
                    }
                },
                {
                    targets: 7,//action buttons
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
                <th></th>
            </tr>
            </thead>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


