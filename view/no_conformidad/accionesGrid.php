<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-acciones').DataTable({
            responsive: true,
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "nc_acciones";
                    d.operation = "refreshGrid";
                    d.id_no_conformidad = $('#etapas_left_side #add').attr('id_no_conformidad');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "fecha_implementacion"},
                {"data" : "accion"},
                {"data" : "user"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_accion);
            },
            "columnDefs": [
                //{ targets: 0, responsivePriority: 2 },
                {
                    targets: 2,//user
                    render: function (data, type, row, meta) {
                        return row.user.split('@')[0];
                    }
                },
                {
                    targets: 3,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        return '<a class="view" title="Ver" href="#">'+
                                    '<i class="far fa-eye dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditar+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminar+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>';
                    }
                }
            ]
        });

        setTimeout(function () {
                    t.columns.adjust();
        },200);




    });

</script>


<?php if(isset($view->acciones) && sizeof($view->acciones) > 0) {?>
    
    <div id="empleados-table">
            <table id="table-acciones" class="table table-condensed dpTable table-hover dt-responsive nowrap">
                <thead>
                <tr>
                    <th>Fecha impl.</th>
                    <th>Acción</th>
                    <th>Usr.</th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>




<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> La No conformidad no tiene acciones registradas.
    </div>

<?php } ?>





