<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

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
                    d.action = "nc_no_conformidad";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nro_no_conformidad"},
                {"data" : "fecha_implementacion"},
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
                    targets: 6,//estado
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        if(row.fecha_cierre) return 'CERRADA';
                        else if(row.cant_acciones > 0) return 'PENDIENTE';
                        else return 'ABIERTA';
                    }
                },
                {
                    targets: 7,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoAcciones = 'acciones';
                        let permisoVerificaciones = 'verificaciones';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'delete' : 'disabled' ?>';
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
                                '<a target="_blank" href="'+link+'" title="Emitir certificado">'+
                                    '<i class="far fa-file-pdf dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a target="_blank" href="#" title="'+user_info+'">'+
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
                <th>Nro. NC</th>
                <th>Fecha impl.</th>
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










