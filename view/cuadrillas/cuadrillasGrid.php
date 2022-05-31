<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            "order": [[0, "asc"]], // 0=Nombre
            columnDefs: [
                { responsivePriority: 1, targets: 4 }
            ]
        });*/


        $('#example').DataTable({
            responsive: true,
            language: {
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            "fnInitComplete": function () {
                $(this).show();
            },
            'order': [[7, "asc"], [0, "asc"]], //7=disabled (oculta), 0=nombre
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.search_contrato = $("#search_contrato").val();
                    d.action = "cuadrillas";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nombre"},
                {"data" : "nombre_corto"},
                {"data" : "nombre_corto_op"},
                {"data" : "contrato"},
                {"data" : "area"},
                {data: null, defaultContent: '', orderable: false},
                {data: null, defaultContent: '', orderable: false},
                {"data" : "disabled", visible: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_cuadrilla);
            },
            "columnDefs": [
                {
                    targets: 5,//activa
                    className: "text-center",
                    responsivePriority: 4,
                    render: function (data, type, row, meta) {
                        let rta = (row.disabled != 1)? '<i class="fas fa-check-circle fa-fw dp_green" title="activa"></i>' : '<i class="fas fa-minus-circle fa-fw dp_red" title="desactivada"></i>';
                        return rta;
                    }
                },
                {
                    targets: 6,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoEmpleados = '<?php echo ( PrivilegedUser::dhasAction('CUA_UPDATE', array(1)) )? 'empleados' : 'disabled' ?>';
                        let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('CUA_VER', array(1)) )? 'view' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('CUA_UPDATE', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('CUA_DELETE', array(1)) )? 'delete' : 'disabled' ?>';

                        return '<a class="'+permisoEmpleados+'" href="#" title="Empleados">'+ //si tiene permiso para agregar empleados
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="view" title="Ver" href="#">'+ //si tiene permiso para ver
                            '<i class="far fa-sticky-note dp_blue"></i>'+
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







    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">



        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>ID SEIP</th>
                <th>ID Operadora</th>
                <th>Contrato</th>
                <th>Área</th>
                <th>Activa</th>
                <th></th>
            </tr>
            </thead>
        </table>


</div>

<div class="col-md-1"></div>



<div id="confirm">

</div>








