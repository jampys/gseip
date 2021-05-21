﻿<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
                                $(this).show();
            },
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "nc_no_conformidad";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "id_no_conformidad"},
                {"data" : "created_date"},
                {"data" : "nombre"},
                {"data" : "tipo"},
                {"data" : "tipo_accion"},
                {"data" : "responsable_seguimiento"},
                {data: null, defaultContent: ''},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_no_conformidad);
            },
            "columnDefs": [
                {
                    targets: 7,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoAcciones = 'acciones';
                        return '<a class="'+permisoAcciones+'" href="#" title="Acciones">'+ //si tiene permiso para ver Acciones
                                    '<i class="fas fa-th-list dp_blue"></i>'+
                                '</a>';
                    }
                }
            ]


        });



    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">





    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro. NC</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Tipo acción</th>
                <th>Resp. Seguimiento</th>
                <th>Estado</th>
                <th></th>

            </tr>
            </thead>
        </table>


    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->










