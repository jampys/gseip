<script type="text/javascript">


    $(document).ready(function(){

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                $(this).show();
            },
            "stateSave": true,
            columnDefs: [
                { responsivePriority: 1, targets: 4 }
            ]
        });*/


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
                    d.search_contrato = $("#search_contrato").val();
                    d.cerrado = $('#periodo option:selected').attr('cerrado');
                    d.search_puesto = $("#search_puesto").val();
                    d.search_nivel_competencia = $('#search_nivel_competencia').val();
                    d.search_localidad = $("#search_localidad").val();
                    d.action = "evaluaciones";
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
                {"data" : "apellido"},
                {"data" : "nombre"},
                {"data" : "contrato"},
                {"data" : "puesto"},
                {"data" : "id_empleado", orderable: false}
            ],
            //"order": [[ 3, 'desc' ], [ 10, 'desc' ]], //fecha_calibracion, id_calibracion
            createdRow: function (row, data, dataIndex) {
                $(row).attr('id_empleado', data.id_empleado);
                $(row).attr('id_plan_evaluacion', data.id_plan_evaluacion);
                $(row).attr('periodo', data.periodo);
                $(row).attr('cerrado', data.cerrado);
            },
            "columnDefs": [
                {
                    targets: 4,//action buttons
                    responsivePriority: 3,
                    render: function (data, type, row, meta) {
                        let permisoAg = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_AGS', array(1)) )? true : false ?>';
                        let permisoCom = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(0)) )? true : false ?>';
                        let permisoComS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) )? true : false ?>';
                        let permisoComIS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) )? true : false ?>';
                        let permisoObj = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_OBJ', array(0)) )? true : false ?>';
                        let permisoObjS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) )? true : false ?>';
                        let permisoObjIS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_OBJ', array(51)) )? true : false ?>';
                        let permisoConcl = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(0)) )? true : false ?>';
                        let permisoConclS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) )? true : false ?>';
                        let permisoConclIS = '<?php echo ( PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) )? true : false ?>';

                        let permisoAgEditar = (!row.cerrado && permisoAg)? 'loadEaag' : 'disabled';
                        let permisoAgIcon = (row.hasAllEaag == 1)? 'glyphicon glyphicon-check dp_green' : 'glyphicon glyphicon-unchecked dp_blue';

                        let permisoComEditar = (!row.cerrado && ( (permisoCom) || (permisoComIS && row.isInSup) || (permisoComS && row.isSup) ))? 'loadEac' : 'disabled';
                        let permisoComIcon = (row.hasAllEac == 1)? 'glyphicon glyphicon-check dp_green' : 'glyphicon glyphicon-unchecked dp_blue';

                        let permisoObjEditar = (!row.cerrado && ( (permisoObj) || (permisoObjIS && row.isInSup) || (permisoObjS && row.isSup) ))? 'loadEao' : 'disabled';
                        let permisoObjIcon = (row.hasAllEao == 1)? 'glyphicon glyphicon-check dp_green' : 'glyphicon glyphicon-unchecked dp_blue';

                        let permisoConclEditar = (!row.cerrado && ( (permisoConcl) || (permisoConclIS && row.isInSup) || (permisoConclS && row.isSup) ))? 'loadEaconcl' : 'disabled';
                        let permisoConclIcon = (row.hasEaconcl == 1)? 'glyphicon glyphicon-check dp_green' : 'glyphicon glyphicon-unchecked dp_blue';

                        let permisoRepInd = ( (permisoCom) || (permisoComIS && row.isInSup) || (permisoComS && row.isSup) )? 'reporte' : 'disabled';


                        return '<a class="'+permisoAgEditar+'" href="#" title="Evaluación aspectos generales">'+ //si tiene permiso para evaluar aspectos generales
                                    '<i class="'+permisoAgIcon+'"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoComEditar+'" href="#" title="Evaluación competencias">'+ //si tiene permiso para evaluar competencias
                                    '<i class="'+permisoComIcon+'"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoObjEditar+'" href="#" title="Evaluación objetivos">'+ //si tiene permiso para evaluar objetivos
                                    '<i class="'+permisoObjIcon+'"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoConclEditar+'" href="#" title="Conclusiones">'+ //si tiene permiso para escribir conclusiones
                                    '<i class="'+permisoConclIcon+'"></i>'+
                                '</a>&nbsp;&nbsp;'+
                                '<a class="'+permisoRepInd+'" href="#" title="Certificado de evaluación">'+ //si tiene permiso emitir el reporte individual
                                    '<i class="fas fa-download dp_blue"></i>'+
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
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Puesto</th>
                <th></th>
            </tr>
            </thead>
        </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->











