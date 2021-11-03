<script type="text/javascript">


    $(document).ready(function(){

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                $(this).show();
            },
            "stateSave": true,
            columnDefs: [
                { responsivePriority: 1, targets: 4 }
            ]
        });*/


        var table = $('#example').DataTable({
            responsive: true,
            deferRender: true,
            //processing: true,
            language: {
                //url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            'ajax': {
                "type"   : "POST",
                //"url"    : 'index.php?action=ajax_certificados&operation=refreshGrid',
                "url"    : 'index.php',
                //data: {action: "ajax_certificados", operation:"refreshGrid"},
                "data": function ( d ) { //https://datatables.net/reference/option/ajax.data
                    d.search_periodo = $("#search_periodo").val();
                    d.search_puesto = $("#search_puesto").val();
                    d.search_area = $("#search_area").val();
                    d.search_contrato = $("#search_contrato").val();
                    d.search_indicador = $("#search_indicador").val();
                    d.search_responsable_ejecucion = $("#search_responsable_ejecucion").val();
                    d.search_responsable_seguimiento = $("#search_responsable_seguimiento").val();
                    d.todos = $('#search_todos').prop('checked')? 1:0;
                    d.action = "obj_objetivos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            //rowId: 'id_objetivo',
            'columns': [
                /*{   // Responsive control column
                 data: null,
                 defaultContent: '',
                 className: 'control',
                 orderable: false
                 },*/
                {"data" : "id_empleado"},
                {"data" : "id_empleado"},
                {"data" : "id_empleado"},
                {"data" : "id_empleado"},
                {"data" : "id_empleado"},
                {"data" : "id_empleado"},
                {"data" : "id_empleado", orderable: false}
            ],
            //"order": [[ 3, 'desc' ], [ 10, 'desc' ]], //fecha_calibracion, id_calibracion
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_objetivo);
                $(row).attr('id_objetivo', data.id_objetivo);
                $(row).attr('cerrado', data.cerrado);
            },
            "columnDefs": [
                {
                    targets: 4,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoEtapas = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'detalle' : 'disabled' ?>';
                        let permisoClonar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>';
                        let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? 'view' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('OBJ_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarO = (permisoEditar && !row.cerrado)? 'edit' : 'disabled';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('OBJ_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarO = (permisoEliminar && !row.cerrado)? 'delete' : 'disabled';
                        let user_info = row.fecha; //row.user.split('@')[0]+' '+row.fecha;
                        return '<a class="'+permisoEtapas+'" href="javascript:void(0);">'+ //si tiene permiso para ver etapas
                            '<i class="fas fa-th-list dp_blue" title="Detalle del objetivo"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoClonar+'" href="#" title="Clonar">'+ //si tiene permiso para clonar
                            '<i class="far fa-copy dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoVer+'" href="#" title="Ver">'+ //si tiene permiso para ver
                            '<i class="far fa-eye dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEditarO+'" href="#" title="Editar">'+ //si tiene permiso para editar
                            '<i class="far fa-edit dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEliminarO+'" href="#" title="Borrar">'+ //si tiene permiso para eliminar
                            '<i class="far fa-trash-alt dp_red"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a href="#" title="'+user_info+'" onclick="return false;">'+
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
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Puesto</th>
                <th></th>
            </tr>
            </thead>
        </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->











