<style>
    .dataTables_scrollHeadInner{  width:100% !important; }
    .dataTables_scrollHeadInner table{  width:100% !important; }
</style>

<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-tareas').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'El objetivo no tiene actividades registradas'
            },
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            autoWidth: false,
            order: [[0, "asc"]], // 0=fecha_implementacion
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "obj_tareas";
                    d.operation = "refreshGrid";
                    d.id_objetivo = $('#myModal #id_objetivo').val(); //$('#etapas_left_side #add').attr('id_objetivo');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nombre"},
                {"data" : "fecha_inicio"},
                {"data" : "fecha_fin"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_tarea);
            },
            "columnDefs": [
                {
                    targets: 0, //nombre
                    width: '50%',
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(50)(data, type, row);
                    }
                },
                {targets: 1, type: 'date-uk'}, //fecha_inicio
                {targets: 2, type: 'date-uk'}, //fecha_fin
                {
                    targets: 3,//action buttons
                    width: '25%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = 'aaaaa'; //row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="avance" title="Avances" href="#">'+
                                    '<i class="fas fa-forward fa-fw dp_blue"></i>'+
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
                            '<a href="#" title="'+user_info+'">'+
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



    <div>

        <table id="table-tareas" class="table table-condensed table-hover dt-responsive" width="100%">
            <thead>
            <tr>
                <th>Actividad</th>
                <th>F. Inicio</th>
                <th>F. Fin</th>
                <th></th>
            </tr>
            </thead>
        </table>


    </div>







