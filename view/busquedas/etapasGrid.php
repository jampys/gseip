<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-etapas').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'La postulación aún no tiene etapas registradas'
            },
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            order: [[0, "desc"]], // 0=fecha_implementacion
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "etapas";
                    d.operation = "refreshGrid";
                    d.id_postulacion = $('#etapas_left_side').attr('id_postulacion');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "fecha_etapa"},
                {"data" : "etapa"},
                {data: null, defaultContent: ''},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_etapa);
            },
            "columnDefs": [
                //{ targets: 0, responsivePriority: 2 },
                {targets: 0, type: 'date-uk'}, //fecha_etapa
                {
                    targets: 1, //etapa
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(25)(data, type, row);
                    }
                },
                {
                    targets: 2,//aplica
                    width: '15%',
                    render: function (data, type, row, meta) {
                        return (row.aplica == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>';
                    }
                },
                {
                    targets: 3,//action buttons
                    width: '20%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let etp_update = '<?php echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)))? true : false ?>';
                        let etp_delete = '<?php echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)))? true : false ?>';
                        let id_user = '<?php echo $_SESSION['id_user'] ?>';
                        let usr_abm = '<?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(0)))? true : false ?>'; //solo el administrador
                        let permisoEditar = ((etp_update && row.id_user == id_user) || usr_abm)? 'edit' : 'disabled';
                        let permisoEliminar = ((etp_update && row.id_user == id_user) || usr_abm)? 'delete' : 'disabled';
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
            <table id="table-etapas" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Etapa</th>
                    <th>Aplica</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

