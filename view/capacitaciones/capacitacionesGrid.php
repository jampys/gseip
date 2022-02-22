

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
                    d.action = "cap_capacitaciones";
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
                {"data" : "categoria"},
                {"data" : "tema"},
                {"data" : "descripcion"},
                {"data" : "mes_programada"},
                {"data" : "cant_participantes"},
                {"data" : "sum_hs"},
                {"data" : null, orderable: false}
            ],
            //"order": [[ 3, 'desc' ], [ 10, 'desc' ]], //fecha_calibracion, id_calibracion
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_capacitacion);
                //$(row).attr('id_objetivo', data.id_objetivo);
                //$(row).attr('cerrado', data.cerrado);
            },
            "columnDefs": [
                {
                    targets: 2,//descripcion
                    responsivePriority: 2,
                    render: function (data, type, row, meta) {
                        return $.fn.dataTable.render.ellipsis(60)(row.descripcion, type, row);
                    }
                },
                {
                    targets: 6,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoEdiciones = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'ediciones' : 'disabled' ?>';
                        let permisoClonar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>';
                        let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? 'view' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('OBJ_UPDATE', array(1)) )? true : false ?>';
                        let permisoEditarO = (permisoEditar && !row.cerrado)? 'edit' : 'disabled';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasAction('OBJ_DELETE', array(1)) )? true : false ?>';
                        let permisoEliminarO = (permisoEliminar && !row.cerrado)? 'delete' : 'disabled';
                        let id_contrato = ($("#id_contrato").val()!= null)? $("#id_contrato").val() : '';
                        let link = 'index.php?action=cap_capacitaciones&operation=pdf&id_capacitacion='+row.id_capacitacion+'&id_contrato='+id_contrato;
                        let user_info = row.user.split('@')[0]+' '+row.created_date;
                        return  '<a class="'+permisoEdiciones+'" href="#">'+ //si tiene permiso para ver ediciones
                                    '<i class="fas fa-th-list dp_blue" title="Ediciones"></i>'+
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


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Tipo cap.</th>
                <th>Tema</th>
                <th>Descripcion</th>
                <th>Mes programada</th>
                <th title="Cantidad de participantes para el/los contratos seleccionados">Cant. part.</th>
                <th title="Sumatoria total de horas de asistencia">Sum. hs.</th>
                <th></th>
            </tr>
            </thead>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


