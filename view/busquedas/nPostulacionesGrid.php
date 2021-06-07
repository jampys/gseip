<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-acciones').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'No existen candidatos para la búsqueda seleccionada'
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
                    d.action = "postulaciones2";
                    d.operation = "refreshGrid";
                    d.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "postulante"},
                {"data" : "etapa"},
                {data: null, defaultContent: ''},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_postulacion);
            },
            "columnDefs": [
                {
                    targets: 0, //postulante
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(50)(data, type, row);
                    }
                },
                {
                    targets: 1, //etapa
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(30)(data, type, row);
                    }
                },
                {
                    targets: 2,//aplica
                    width: '10%',
                    responsivePriority: 2,
                    render: function (data, type, row, meta) {
                        let aplica = (row.aplica == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>';
                        return '<a class="etapas" title="Etapas" href="#">'+aplica+'</a>';
                    }
                },
                {
                    targets: 3,//action buttons
                    width: '20%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = row.user.split('@')[0]+' '+row.fecha;
                        return '<a class="view" title="Ver" href="#">'+
                                    '<i class="far fa-eye dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditar+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminar+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a target="_blank" href="#" title="'+user_info+'">'+
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
            <table id="table-acciones" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Postulante</th>
                    <th>Ult. etapa</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

