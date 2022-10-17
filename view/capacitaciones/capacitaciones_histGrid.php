

<script type="text/javascript">


    $(document).ready(function(){

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
                    d.periodo = $("#periodo").val();
                    d.id_categoria = $("#id_categoria").val();
                    d.mes_programada = $("#mes_programada").val();
                    d.id_contrato = ($("#id_contrato").val()!= null)? $("#id_contrato").val() : '';
                    d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.action = "cap_capacitaciones_hist";
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
                {"data" : "fecha_edicion"},
                {"data" : "empleado"},
                {"data" : "categoria"},
                {"data" : "tema"},
                {"data" : "modalidad"},
                {"data" : "capacitador"},
                {"data" : "duracion"},
                {"data" : null, orderable: false}
            ],
            "order": [[ 0, 'desc' ], [ 1, 'desc' ]], //fecha, empleado
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_capacitacion);
            },
            "columnDefs": [
                {targets: 0, type: 'date-uk', orderData: [ 0, 1]}, //fecha
                {
                    targets: 7,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoEdiciones = '<?php echo ( PrivilegedUser::dhasPrivilege('CAP_ABM', array(1)) )? 'ediciones' : 'disabled' ?>';
                        let permisoClonar = '<?php echo ( PrivilegedUser::dhasPrivilege('CAP_ABM', array(1)) )? 'clone' : 'disabled' ?>';
                        let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('CAP_VER', array(1)) )? 'view' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('CAP_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarO = (permisoEditar && !row.cerrado)? 'edit' : 'disabled';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('CAP_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarO = (permisoEliminar && !row.cerrado)? 'delete' : 'disabled';
                        let id_contrato = $("#id_contrato").val();
                        let startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                        let endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                        let link = 'index.php?action=cap_capacitaciones&operation=pdf&id_capacitacion='+row.id_capacitacion+'&id_contrato='+id_contrato+'&startDate='+startDate+'&endDate='+endDate;
                        let user_info = ''; //row.user.split('@')[0]+' '+row.created_date;
                        return  '<a class="'+permisoVer+'" href="#" title="Ver">'+ //si tiene permiso para ver
                                    '<i class="far fa-sticky-note dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEditarO+'" href="#" title="Editar">'+ //si tiene permiso para editar
                                    '<i class="far fa-edit dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoEliminarO+'" href="#" title="Borrar">'+ //si tiene permiso para eliminar
                                    '<i class="far fa-trash-alt dp_red"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a target="_blank" href="'+link+'" title="Descargar reporte">'+
                                    '<i class="fas fa-download dp_blue"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a href="#" title="'+user_info+'" onclick="return false;">'+
                                    '<i class="fa fa-question-circle dp_light_gray"></i>'+
                                '</a>';
                    }
                }
            ],
            "footerCallback": function ( row, data, start, end, display ) { //https://datatables.net/examples/advanced_init/footer_callback.html
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total participantes over all pages
                totalP = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total participantes over this page
                pageTotalP = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 6 ).footer() ).html(pageTotalP +' ('+ totalP +' total)');
            }

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
                <th>Empleado</th>
                <th>Tipo cap.</th>
                <th>Tema</th>
                <th>Modalidad</th>
                <th>Capacitador</th>
                <th>Duración [hs]</th>
                <th></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


