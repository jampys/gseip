﻿
<script type="text/javascript">


    $(document).ready(function(){


        /*var table = $('#example').DataTable({
            responsive: true,
            "stateSave": true,
            "fnInitComplete": function () {
                $(this).show();
            },
            columnDefs: [
                {targets: 2, render: $.fn.dataTable.moment('DD/MM/YYYY')}, //fecha_alta
                {targets: 5, render: $.fn.dataTable.moment('DD/MM/YYYY HH:mm')}, //ult_acceso
                {targets: 6,    responsivePriority: 1} //action buttons
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
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.search_responsable_ejecucion = $('#search_responsable_ejecucion').val();
                    d.action = "sec_users";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "id_user"},
                {"data" : "id_user"},
                {"data" : "id_user"},
                {"data" : "id_user"},
                {"data" : "id_user"},
                {"data" : "id_user"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_user);
            },
            "columnDefs": [
                {
                    targets: 7,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoAcciones = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'acciones' : 'disabled' ?>';
                        let permisoVerificaciones = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'verificaciones' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) )? 'delete' : 'disabled' ?>';
                        let link = 'index.php?action=nc_no_conformidad&operation=pdf&id_no_conformidad='+row.id_no_conformidad;
                        let user_info =''; //row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="'+permisoAcciones+'" href="#" title="Acciones">'+ //si tiene permiso para ver Acciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoVerificaciones+'" href="#" title="Verificaciones">'+ //si tiene permiso para ver Verificaciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
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
                            '<a target="_blank" href="'+link+'" title="Descargar certificado">'+
                            '<i class="fas fa-download dp_blue"></i>'+
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


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Usuarios</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) )? '' : 'disabled' ?> >
            <i class="fas fa-plus dp_green"></i> Nuevo Usuario
        </button>
    </div>

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Usuario</th>
                <th>Habilitado</th>
                <th>F. Alta</th>
                <th>F. Baja</th>
                <th>Empleado</th>
                <th>Ult. acceso</th>
                <th></th>
            </tr>
            </thead>
        </table>

    <!--</div>-->

</div>

<div class="col-md-1"></div>

