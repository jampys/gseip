<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-empleados').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'La cuadrilla aún no tiene empleados registrados'
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
                    d.action = "cuadrilla-empleado";
                    d.operation = "refreshGrid";
                    d.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
                },
                "dataSrc": ""
            },
            'columns': [
                {data: null, defaultContent: ''}, //empleado
                {data: null, defaultContent: ''}, //conductor
                {data: null, defaultContent: '', orderable: false} //actions
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_cuadrilla_empleado);
            },
            "columnDefs": [
                {
                    targets: 0, //empleado
                    render: function(data, type, row) {
                        return 1; //$.fn.dataTable.render.ellipsis(125)(data, type, row);
                    }
                },
                {
                    targets: 1, //conductor
                    render: function(data, type, row) {
                        return 1; //$.fn.dataTable.render.ellipsis(125)(data, type, row);
                    }
                },
                {
                    targets: 2,//action buttons
                    width: '20%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = 1; //row.user.split('@')[0]+' '+row.created_date;
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


    <table id="table-empleados" class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <th>Empleado</th>
            <th>Ctor.</th>
            <th></th>
        </tr>
        </thead>
    </table>






