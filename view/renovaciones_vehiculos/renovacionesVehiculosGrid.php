<script type="text/javascript">


    $(document).ready(function(){


        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            "order": [[6, "asc"], [7, "asc"], [5, "asc"] ], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 6 ]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 6 ]}, //fecha_emision
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 6 ]}, //fecha_vencimiento
                {targets: [ 6 ], orderData: [ 6], visible: false}, //priority
                {targets: [ 7 ], orderData: [ 7], visible: false}, //renovacion
                { responsivePriority: 1, targets: 8 }
            ]
        });*/



        $('#example').DataTable({
            dom: "<'row'<'col-md-8'l><'col-md-1'B><'col-md-3'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-5'i><'col-md-7'p>>",
            buttons: [
                {
                    text: '<i class="fas fa-file-excel fa-lg dp_blue"></i>',
                    titleAttr: 'Descargar RV02 Reporte de vencimientos de vehículos [xlsx]',
                    action: function ( e, dt, node, config ) {

                        params={};
                        var attr = $('#search_vehiculo option:selected').attr('id_vehiculo'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
                        params.id_vehiculo = (typeof attr !== typeof undefined && attr !== false)? $('#search_vehiculo option:selected').attr('id_vehiculo') : '';
                        var attr = $('#search_vehiculo option:selected').attr('id_grupo');
                        params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_vehiculo option:selected').attr('id_grupo') : '';
                        params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                        params.id_contrato = $("#search_contrato").val();
                        params.id_subcontratista = $("#search_subcontratista").val();
                        params.renovado = $('#search_renovado').prop('checked')? 1 : 0;
                        params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                        //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                        //var URL="<?php echo $GLOBALS['ini']['application']['report_url']; ?>frameset?__format=html&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
                        //var win = window.open(URL, "_blank");
                        //return false;


                        let link = 'index.php?action=renovacionesVehiculos&operation=reporte'+
                            '&id_vehiculo='+params.id_vehiculo+
                            '&id_grupo='+params.id_grupo+
                            '&id_vencimiento='+params.id_vencimiento+
                            '&id_contrato='+params.id_contrato+
                            '&id_subcontratista='+params.id_subcontratista+
                            '&renovado='+params.renovado+
                            '&id_user='+params.id_user;

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
                    //d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    //d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.id_vehiculo = $('#search_vehiculo option:selected').attr('id_vehiculo');
                    d.id_grupo = $('#search_vehiculo option:selected').attr('id_grupo');
                    d.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                    d.id_contrato = $("#search_contrato").val();
                    d.id_subcontratista = $("#search_subcontratista").val();
                    d.renovado = $('#search_renovado').prop('checked')? 1:0;
                    d.action = "renovacionesVehiculos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            "order": [[5, "asc"], [6, "asc"], [4, "asc"] ], //5=priority (oculta), 6=renovacion, 4=fecha_vencimiento
            'columns': [
                {"data" : "id_renovacion"},
                {"data" : "vencimiento"},
                {"data" : null, defaultContent: ''}, //vehiculo/grupo
                {"data" : "fecha_emision"},
                {"data" : "fecha_vencimiento"},
                {"data" : "priority", visible: false},
                {"data" : "id_rnv_renovacion", visible: false},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_renovacion);
            },
            "columnDefs": [
                {
                    targets: 2,//vehiculo/grupo
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        return (row.id_vehiculo)? row.vehiculo : row.grupo;
                    }
                },
                {targets: 3, type: 'date-uk', orderData: [ 3, 5 ]}, //fecha_emision
                {
                    targets: 4, type: 'date-uk', orderData: [ 4, 5 ], //fecha_vencimiento
                    createdCell: function (td, cellData, rowData, row, col) { //https://datatables.net/reference/option/columns.createdCell
                        $(td).css('background-color', rowData.color)
                    }
                },
                {
                    targets: 7,//action buttons
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {

                        let id_user = '<?php echo $_SESSION['id_user'] ?>';
                        let usr_abm = '<?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(0)))? true : false ?>'; //solo el administrador

                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('RVE_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarP = (permisoEditar && !row.id_rnv_renovacion)? 'edit' : 'disabled';

                        let permisoRenovar_class = '';
                        let permisoRenovar_title = '';
                        let permisoRenovar_icon = '';
                        if(row.id_rnv_renovacion){
                            permisoRenovar_class = '';
                            permisoRenovar_title = 'Nro. vto.: '+row.id_rnv_renovacion;
                            permisoRenovar_icon = 'fas fa-check-circle dp_blue';
                        }else if(permisoEditar){
                            permisoRenovar_class = 'renovar';
                            permisoRenovar_title = 'Renovar vencimiento';
                            permisoRenovar_icon = 'fas fa-share dp_blue';
                        }else{
                            permisoRenovar_class = 'disabled';
                            permisoRenovar_title = 'No tiene permisos para renovar';
                            permisoRenovar_icon = 'fas fa-share dp_blue';
                        }


                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('RVE_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarP = ((permisoEliminar && row.created_by == id_user) || (usr_abm))? 'delete' : 'disabled';

                        let user_info = row.user.split('@')[0]+' '+row.created_date;

                        let uploads_class = '';
                        let uploads_title = '';
                        if(row.cant_uploads > 0){
                            uploads_class = 'fas fa-paperclip dp_gray';
                            uploads_title = row.cant_uploads+' adjuntos';
                        }else{
                            uploads_class = 'fas fa-paperclip dp_light_gray disabled';
                            uploads_title = 'sin adjuntos';
                        }

                        return '<a href="#" title="'+uploads_title+'">'+
                            '<i class="'+uploads_class+'"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="view" title="Ver vencimiento" href="#">'+
                            '<i class="far fa-sticky-note dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEditarP+'" href="#" title="Editar vencimiento">'+ //si tiene permiso para editar
                            '<i class="far fa-edit dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoRenovar_class+'" href="#" title="'+permisoRenovar_title+'">'+ //si tiene permiso para renovar
                            '<i class="'+permisoRenovar_icon+'"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoEliminarP+'" href="#" title="Eliminar vencimiento">'+ //si tiene permiso para eliminar
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


        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nro. vto.</th>
                <th>vencimiento</th>
                <th>vehículo / flota</th>
                <th>F. emisión</th>
                <th>F. vto.</th>
                <th style="display: none">Priority</th>
                <th style="display: none">Rnv</th>
                <th></th>

            </tr>
            </thead>
        </table>



</div>

<!--<div class="col-md-1"></div>-->










