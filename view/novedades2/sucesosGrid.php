<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#table-sucesos').DataTable({
            responsive: true,
            language: {
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            sDom: '<"top">rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            "fnInitComplete": function () {
                $(this).show();
            },
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.id_empleado = $('#id_empleado').val();
                    d.id_periodo = $('#id_periodo').val();
                    d.id_contrato = $('#id_contrato').val();
                    d.action = "novedades2";
                    d.operation = "sucesosRefreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[1, "desc"], [2, "desc"] ], //1=fecha_desde, 2=fecha_hasta
            'columns': [
                {"data" : "evento"},
                {"data" : "fecha_desde"},
                {"data" : "fecha_hasta"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_suceso);
            },
            "columnDefs": [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1 ]}, //fecha_desde
                {targets: [ 2 ], type: 'date-uk', orderData: [ 2, 1 ]}, //fecha_hasta
                {
                    targets: 3,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {

                        let permisoVer="";
                        if(!row.programado && row.id_periodo1) permisoVer = 'view';
                        else if (row.programado && !row.id_periodo1) permisoVer = 'viewp';
                        else permisoVer = 'disabled';

                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('SUC_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarS = '';
                        if(permisoEditar && !(row.closed_date_1 && row.closed_date_2) && (!row.programado && row.id_periodo1)) permisoEditarS = 'edit';
                        else if( permisoEditar && !(row.closed_date_1 && row.closed_date_2) && (row.programado && !row.id_periodo1)) permisoEditarS = 'editp';
                        else permisoEditarS = 'disabled';

                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('SUC_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarS = (permisoEliminar && !(row.closed_date_1 && row.closed_date_2))? 'delete' : 'disabled';

                        let user_info = row.created_date; //row.user.split('@')[0]+' '+row.fecha;
                        let link = 'index.php?action=nc_no_conformidad&operation=pdf&id_no_conformidad='+row.id_no_conformidad;

                        return '<a class="'+permisoVer+'" title="Ver" href="#">'+
                            '<i class="far fa-eye dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEditarS+'" href="#" title="Editar">'+ //si tiene permiso para editar
                            '<i class="far fa-edit dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEliminarS+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                            '<i class="far fa-trash-alt dp_red"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a target="_blank" href="'+link+'" title="Descargar certificado">'+
                            '<i class="fas fa-download dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a href="#" title="'+user_info+'">'+
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

    <table id="table-sucesos" class="table table-condensed table-hover dt-responsive" width="100%">
        <thead>
        <tr>
            <th>Evento</th>
            <th>F. desde</th>
            <th>F. hasta</th>
            <th></th>
        </tr>
        </thead>
    </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->





