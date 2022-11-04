<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[1, "desc"], [0, "asc"]], // 2=fecha, 5=nombre
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 0 ]}, //fecha
                {targets: [ 2 ], type: 'date-uk', orderData: [ 2, 0 ]},
                { responsivePriority: 1, targets: 7 }
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
            "order": [[1, "desc"], [0, "asc"]], // 2=fecha_apertura, 5=nombre
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.search_puesto = $("#search_puesto").val();
                    d.search_localidad = $("#search_localidad").val();
                    d.search_contrato = $("#search_contrato").val();
                    d.action = "busquedas";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nombre"},
                {"data" : "fecha_apertura"},
                {"data" : "fecha_cierre"},
                {"data" : "puesto"},
                {"data" : "area"},
                {"data" : "contrato"},
                {"data" : "estado"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_busqueda);
            },
            "columnDefs": [
                {targets: 1, type: 'date-uk', orderData: [ 1, 0 ]}, //fecha_apertura
                {targets: 2, type: 'date-uk', orderData: [ 2, 0 ]}, //fecha_cierre
                {
                    targets: 7,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoPostulaciones = '<?php echo ( PrivilegedUser::dhasPrivilege('PTN_ABM', array(1)) )? 'detalles' : 'disabled' ?>';
                        let adjuntos = (row.cant_uploads > 0)? '<a href="#" title="'+row.cant_uploads+' adjuntos" ><i class="fas fa-paperclip dp_gray"></i></a>&nbsp;&nbsp;' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = row.user.split('@')[0]+' '+row.fecha;
                        return '<a class="'+permisoPostulaciones+'" href="#" title="Postulaciones">'+ //si tiene permiso para editar Postulaciones
                                    '<i class="fas fa-th-list dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                adjuntos+
                                '<a class="view" title="Ver" href="#">'+
                                    '<i class="far fa-sticky-note dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditar+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminar+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a href="#" title="'+user_info+'" onclick="return false;">'+
                                    '<i class="fa fa-question-circle dp_light_gray"></i>'+
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
                <th>Nombre</th>
                <th>F. apertura</th>
                <th>F. cierre</th>
                <th>Puesto</th>
                <th>Área</th>
                <th>Contrato</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>

        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">

</div>








