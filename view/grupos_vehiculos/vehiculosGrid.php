<script type="text/javascript">


    $(document).ready(function(){

        /*var t = $('#table-vehiculos').DataTable({
            responsive: true,
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            order: [[3, "asc"], [1, "asc"]], // 3=fecha_hasta, 1=certif
            columnDefs: [
                { responsivePriority: 1, targets: 4 }
            ]
        });

        setTimeout(function () { //https://datatables.net/forums/discussion/41587/scrolly-misaligned-table-headers-with-bootstrap
            t.columns.adjust();
        },200);*/


        var t = $('#table-vehiculos').DataTable({
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
            order: [[0, "asc"]], // 0=fecha_edicion
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "vto_grupo-vehiculo";
                    d.operation = "refreshGrid";
                    d.id_grupo = $('#etapas_left_side').attr('id_grupo');
                    //d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    //d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "id_vehiculo"},
                {"data" : "id_vehiculo"},
                {"data" : "id_vehiculo"},
                {"data" : "id_vehiculo"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_grupo);
            },
            "columnDefs": [
                {targets: 0, type: 'date-uk', orderData: [ 0, 1 ]}, //fecha_edicion
                {
                    targets: 1, //nombre
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(40)(data, type, row);
                    }
                },
                {
                    targets: 4,//action buttons
                    width: '23%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = ''; //row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="new" title="Participantes" href="#">'+
                            '<i class="fas fa-users dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
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

        setTimeout(function () {
            t.columns.adjust();
        },150);




    });

</script>



    <div id="empleados-table">
            <table id="table-vehiculos" class="table table-condensed dpTable table-hover dt-responsive nowrap">
                <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>Certif.</th>
                    <th>F. desde</th>
                    <th>F. hasta</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>








