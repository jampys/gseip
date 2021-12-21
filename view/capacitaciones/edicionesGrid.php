<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-ediciones').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                search: '',
                searchPlaceholder: "Buscar edicion",
                emptyTable: 'La capacitación no tiene ediciones registradas'
            },
            sDom:   "<'row'<'col-sm-2'B><'col-sm-4'><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12'>>",
            buttons: [
                {
                    text: '<i class="fas fa-plus fa-fw dp_green"></i>',
                    titleAttr: 'Agregar edición',
                    attr:  {
                        id: 'add', //https://datatables.net/reference/option/buttons.buttons.attr
                        disabled: function(){
                            let permisoNuevo = '<?php echo (PrivilegedUser::dhasPrivilege('PTN_ABM', array(1)) )? 'false' : 'true' ?>';
                            return (permisoNuevo == 'false')? false : true;
                        }
                    },
                    action: function ( e, dt, node, config ) {
                        //https://datatables.net/reference/option/buttons.buttons.action
                        //usa el evento que esta en nPostulacionesForm.php
                    }
                }
            ],
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
                    d.action = "cap_ediciones";
                    d.operation = "refreshGrid";
                    d.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "fecha_edicion"},
                {"data" : "nombre"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_edicion);
            },
            "columnDefs": [
                {
                    targets: 1, //nombre
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(40)(data, type, row);
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
                        return '<a class="new" title="Empleados" href="#">'+
                                    '<i class="fas fa-users dp_blue"></i>'+
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



    
    <div id="ediciones-table">
            <table id="table-ediciones" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Edición</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

