<script type="text/javascript">


    $(document).ready(function(){

        /*$('#example').DataTable({
            responsive: true,
            "stateSave": true,
            "order": [[5, "asc"], [0, "asc"]], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            "fnInitComplete": function () {
                $(this).show();
            },
            columnDefs: [
                { responsivePriority: 1, targets: 5 }
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
                    //d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    //d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    //d.search_responsable_ejecucion = $('#search_responsable_ejecucion').val();
                    d.action = "vto_gruposVehiculos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[3, "asc"], [1, "asc"]], //5=fecha_baja, 1=nombre
            'columns': [
                {"data" : "nro_referencia"},
                {"data" : "nombre"},
                {"data" : "vencimiento"},
                {"data" : "fecha_baja"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_grupo);
            },
            "columnDefs": [
                {
                    targets: 4,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoVehiculos = '<?php echo ( PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? 'vehiculos' : 'disabled' ?>';
                        let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? 'view' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('GRV_UPDATE', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('GRV_DELETE', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = ''; //row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="'+permisoVehiculos+'" href="#">'+ //si tiene permiso para ver vehiculos del grupo
                                    '<i class="fas fa-th-list dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoVer+'" href="#" title="Ver">'+ //si tiene permiso para ver
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



    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Flotas de vehículos</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo (PrivilegedUser::dhasAction('GRV_INSERT', array(1)) )? '' : 'disabled' ?> >
            <i class="fas fa-plus dp_green"></i> Nueva Flota
        </button>
    </div>

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro. referencia</th>
                <th>Nombre</th>
                <th>Vencimiento</th>
                <th>Fecha Baja</th>
                <th></th>
            </tr>
            </thead>
        </table>

    </div>

<!--</div>-->

<div class="col-md-1"></div>



