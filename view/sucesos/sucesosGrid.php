<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            //"order": [[1, "asc"], [4, "asc"], [5, "asc"] ], //1=fecha, 4=fecha_desde, 5=fecha_hasta
            "order": [[4, "desc"], [5, "desc"] ], //4=fecha_desde, 5=fecha_hasta
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4]}, //fecha_desde
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 4 ]}, //fecha_hasta
                { responsivePriority: 1, targets: 6 }
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
                    //d.search_responsable_ejecucion = $('#search_responsable_ejecucion').val();
                    d.action = "sucesos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
                {"data" : "id_suceso"},
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
                        let permisoAcciones = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'acciones' : 'disabled' ?>';
                        let permisoVerificaciones = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'verificaciones' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let link = 'index.php?action=nc_no_conformidad&operation=pdf&id_no_conformidad='+row.id_no_conformidad;
                        let user_info = row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="'+permisoAcciones+'" href="#" title="Acciones">'+ //si tiene permiso para ver Acciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoVerificaciones+'" href="#" title="Verificaicones">'+ //si tiene permiso para ver Verificaciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="view" title="Ver" href="#">'+
                            '<i class="far fa-eye dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEditar+'" href="#" title="Editar">'+ //si tiene permiso para editar
                            '<i class="far fa-edit dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEliminar+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                            '<i class="far fa-trash-alt dp_red"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a target="_blank" href="'+link+'" title="Descargar certificado">'+
                            '<i class="fas fa-download dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a href="#" title="'+user_info+'">'+
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
                <th>Nro. Suceso</th>
                <th>Fecha</th>
                <th>Evento</th>
                <th>Empleado</th>
                <th>F. desde</th>
                <th>F. hasta</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
        </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->

