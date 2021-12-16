<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-empleados').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'La capacitación no tiene empleados registrados'
            },
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            order: [[0, "asc"]], // 0=fecha_implementacion
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "cap_empleados";
                    d.operation = "refreshGrid";
                    d.id_capacitacion = $('#etapas_left_side #add').attr('id_capacitacion');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "empleado"},
                {"data" : "contrato"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_capacitacion_empleado);
            },
            "columnDefs": [
                {
                    targets: 0, //empleado
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(30)(data, type, row);
                    }
                },
                {
                    targets: 1, //contrato
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(25)(data, type, row);
                    }
                },
                {
                    targets: 2,//action buttons
                    width: '20%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="view" title="Ver" href="#">'+
                                    '<i class="far fa-eye dp_blue"></i>'+
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

        setTimeout(function () {
                    t.columns.adjust();
        },150);




    });

</script>



    
    <div id="empleados-table">
            <table id="table-empleados" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Contrato</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

