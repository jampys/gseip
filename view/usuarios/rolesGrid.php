<script type="text/javascript">


    $(document).ready(function(){

        /*var t = $('#table-vehiculos').DataTable({
            responsive: true,
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            columnDefs: [
                { responsivePriority: 1, targets: 3 }
            ]
        });*/

        var t = $('#table-roles').DataTable({
            responsive: true,
            language: {
                //url: 'resources/libraries/dataTables/Spanish.json',
                search: '',
                searchPlaceholder: "Buscar rol",
                emptyTable: 'El usuario no tiene roles otorgados.'
                //"sInfo":           "Mostrando _TOTAL_ registros",
                //"sInfoEmpty":      "Mostrando 0 registros",
                //"sInfoFiltered":   ""
            },
            sDom:   "<'row'<'col-sm-2'B><'col-sm-4'><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'>>",
            buttons: [
                {
                    text: '<i class="fas fa-plus fa-fw dp_green"></i>',
                    titleAttr: 'Agregar rol',
                    attr:  {
                        id: 'add', //https://datatables.net/reference/option/buttons.buttons.attr
                        disabled: function(){
                            let permisoNuevo = '<?php echo (PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) )? 'false' : 'true' ?>';
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
                    d.action = "sec_user-role";
                    d.operation = "refreshGrid";
                    d.id_user = $('#etapas_left_side').attr('id_user');
                    //d.id_edicion = $('#etapas_left_side').attr('id_edicion');
                    //d.id_contrato = ($("#id_contrato").val()!= null)? $("#id_contrato").val() : '';
                    //d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    //d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nombre"},
                {"data" : "fecha_desde"},
                {"data" : "fecha_hasta"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_user_role);
            },
            "columnDefs": [
                {
                    targets: 0, //nombre
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(40)(data, type, row);
                    }
                },
                {targets: 1, render: $.fn.dataTable.moment('DD/MM/YYYY')}, //fecha_desde
                {targets: 2, render: $.fn.dataTable.moment('DD/MM/YYYY')}, //fecha_hasta
                {
                    targets: 3,//action buttons
                    width: '18%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = row.userc.split('@')[0]+' '+row.created_date;
                        return '<a class="view" title="Ver" href="#">'+
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



        setTimeout(function () { //https://datatables.net/forums/discussion/41587/scrolly-misaligned-table-headers-with-bootstrap
            //$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            t.columns.adjust();
        },150);


    });

</script>



    <div>
            <table id="table-roles" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Rol</th>
                    <th>F. desde</th>
                    <th>F. hasta</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

