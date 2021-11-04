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


                        //let permisoClonar = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>';
                        //let permisoVer = '<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? 'view' : 'disabled' ?>';
                        //let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('OBJ_UPDATE', array(1)) )? true : false ?>';
                        //let permisoEditarO = (permisoEditar && !row.cerrado)? 'edit' : 'disabled';

                        let permisoAgEditar = (!row.cerrado && permisoAg)? 'loadEaag' : 'disabled';
                        let permisoAgIcon = (row.hasAllEaag == 1)? 'far fa-check-square dp_green' : 'far fa-square dp_blue';


                        return '<a class="'+permisoAgEditar+'" href="javascript:void(0);">'+ //si tiene permiso para evaluar aspectos generales
                                    '<i class="'+permisoAgIcon+'" title="Evaluación aspectos generales"></i>'+
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











