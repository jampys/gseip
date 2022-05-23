<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[0, "desc"], [1, "asc"], [2, "asc"]],
            columnDefs: [
                {targets: [ 0 ], type: 'date-uk', orderData: [ 0, 1 ]},
                { responsivePriority: 1, targets: 8 },
                { responsivePriority: 2, targets: 6 }
            ]
        });*/

        $('#example').DataTable({
            dom: "<'row'<'col-md-7'l><'col-md-2'B><'col-md-3'f>>" +
                 "<'row'<'col-md-12'tr>>" +
                 "<'row'<'col-md-5'i><'col-md-7'p>>",
            buttons: [
                {
                    text: '<i class="far fa-file-pdf fa-lg dp_blue"></i>',
                    titleAttr: 'Emitir RN01 Reporte de actividad de cuadrillas',
                    action: function ( e, dt, node, config ) {
                        let link = 'index.php?action=partes&operation=reporte'+
                            '&id_contrato='+$('#add_contrato').val()+
                            '&cuadrilla='+$('#cuadrilla').val()+
                            '&target=pdf'+
                                //'&startDate='+drp.startDate.format('YYYY-MM-DD')+
                                //'&endDate='+drp.endDate.format('YYYY-MM-DD');
                            '&fecha_desde='+$('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD')+
                            '&fecha_hasta='+$('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        window.open(link, '_blank');
                    }
                },
                {
                    text: '<i class="far fa-file-excel fa-lg dp_blue"></i>',
                    titleAttr: 'Emitir RN02 Reporte de actividad de cuadrillas',
                    action: function ( e, dt, node, config ) {
                        let link = 'index.php?action=partes&operation=reporte'+
                            '&id_contrato='+$('#add_contrato').val()+
                            '&cuadrilla='+$('#cuadrilla').val()+
                            '&target=excel'+
                                //'&startDate='+drp.startDate.format('YYYY-MM-DD')+
                                //'&endDate='+drp.endDate.format('YYYY-MM-DD');
                            '&fecha_desde='+$('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD')+
                            '&fecha_hasta='+$('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        //window.open(link);
                        window.location.href = link;
                    }
                }
            ],
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
                    d.search_contrato = $("#add_contrato").val();
                    d.id_periodo = $("#id_periodo").val();
                    d.cuadrilla = $("#cuadrilla").val();
                    d.action = "partes";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[0, "desc"], [1, "asc"], [2, "asc"]],
            'columns': [
                {"data" : "fecha_parte"},
                {"data" : "id_parte"},
                {"data" : "contrato"},
                {"data" : "cuadrilla"},
                {"data" : "area"},
                {"data" : "evento"},
                {data: null, defaultContent: '', orderable: false},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_parte);
            },
            "columnDefs": [
                {targets: 0, type: 'date-uk', orderData: [ 0, 1 ]}, //fecha_parte
                {
                    targets: 6,//botones indicadores
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let novedad = (row.id_parte)? '<i class="fas fa-truck-pickup fa-fw dp_blue_nov" title="con novedad"></i>':'<i class="fas fa-car fa-fw dp_light_gray" title="sin novedad"></i>';
                        let conceptos = (row.concept_count > 0)? '<i class="fas fa-calculator fa-fw dp_blue_nov" title="novedad con conceptos"></i>':'<i class="fas fa-calculator fa-fw dp_light_gray" title="novedad sin conceptos"></i>';
                        let ordenes = (row.orden_count > 0)? '<i class="fas fa-clipboard-check fa-fw dp_blue_nov" title="novedad con órdenes"></i>':'<i class="fas fa-clipboard fa-fw dp_light_gray" title="novedad sin órdenes"></i>';
                        return '<a href="#">'+
                                    novedad+
                                '</a>&nbsp;&nbsp;'+
                                '<a href="#">'+
                                    conceptos+
                                '</a>&nbsp;&nbsp;'+
                                '<a href="#">'+
                                    ordenes+
                                '</a>';
                    }
                },
                {
                    targets: 7,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let id_user = '<?php echo $_SESSION['id_user'] ?>';
                        let usr_abm = '<?php echo ( PrivilegedUser::dhasPrivilege('SUC_ABM', array(0)))? true : false ?>'; //solo el administrador

                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('PAR_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarP = (permisoEditar && !row.closed_date)? 'edit' : 'disabled';

                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('PAR_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarP = ( !row.closed_date && ( (permisoEliminar && row.created_by == id_user) || (usr_abm) ))? 'delete' : 'disabled';

                        let user_info = row.user.split('@')[0]+' '+row.created_date;

                        return '<a class="view" title="Ver novedad" href="#">'+
                                    '<i class="far fa-eye dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditarP+'" href="#" title="Editar novedad">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminarP+'" href="#" title="Eliminar novedad">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
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

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Fecha</th>
                <th title="Identificador de Novedad">IN</th>
                <th>Contrato</th>
                <th>Cuadrilla / Empleado</th>
                <th>Área</th>
                <th>Evento</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


