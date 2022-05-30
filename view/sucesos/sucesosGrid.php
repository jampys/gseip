<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

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
                    d.id_empleado = $('#id_empleado').val();
                    d.eventos = $('#eventos').val();
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.id_contrato = $('#id_contrato').val();
                    d.action = "sucesos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[4, "desc"], [5, "desc"] ], //3=fecha_desde, 4=fecha_hasta
            'columns': [
                {"data" : "id_suceso"},
                {"data" : "evento"},
                {"data" : "empleado"},
                {"data" : "cantidad"},
                {"data" : "fecha_desde"},
                {"data" : "fecha_hasta"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_suceso);
            },
            "columnDefs": [
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4 ]}, //fecha_desde
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 4 ]}, //fecha_hasta
                {
                    targets: 6,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {

                        let id_user = '<?php echo $_SESSION['id_user'] ?>';
                        let usr_abm = '<?php echo ( PrivilegedUser::dhasPrivilege('SUC_ABM', array(0)))? true : false ?>'; //solo el administrador

                        let permisoVer="";
                        //if(!row.programado && row.id_periodo1) permisoVer = 'view';
                        //else if (row.programado && !row.id_periodo1) permisoVer = 'viewp';
                        if(row.id_periodo1) permisoVer = 'view';
                        else if (!row.id_periodo1) permisoVer = 'viewp';
                        else permisoVer = 'disabled';

                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('SUC_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarS = '';
                        if(permisoEditar && !(row.closed_date_1 && row.closed_date_2) && (!row.programado && row.id_periodo1) && (row.id_user == id_user || usr_abm) ) permisoEditarS = 'edit';
                        else if( permisoEditar && !(row.closed_date_1 && row.closed_date_2) && (row.programado && !row.id_periodo1) && (row.id_user == id_user || usr_abm) ) permisoEditarS = 'editp';
                        else permisoEditarS = 'disabled';

                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('SUC_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarS = (permisoEliminar && !(row.closed_date_1 && row.closed_date_2) && (row.id_user == id_user || usr_abm) )? 'delete' : 'disabled';

                        let user_info = row.user.split('@')[0]+' '+row.created_date;

                        let link, link1 = "";
                        if(row.id_evento == 21){
                            link = 'index.php?action=sucesos&operation=pdf_21&id_suceso='+row.id_suceso;
                            link1 = '';
                        }else{
                            link = '#';
                            link1 = 'onclick="return false"; class="disabled"';
                        }


                        return '<a class="'+permisoVer+'" title="Ver" href="#">'+
                                    '<i class="far fa-sticky-note dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditarS+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminarS+'" href="#" title="Eliminar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a target="_blank" href="'+link+'" '+link1+' title="Descargar notificación">'+
                                    '<i class="fas fa-download dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a href="#" title="'+user_info+'" onclick="return false">'+
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
                <th>Nro. Suceso</th>
                <th>Evento</th>
                <th>Empleado</th>
                <th>Cant.</th>
                <th>F. desde</th>
                <th>F. hasta</th>
                <th></th>
            </tr>
            </thead>
        </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->

