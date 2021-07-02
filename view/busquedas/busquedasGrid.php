﻿<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        /*$('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[1, "desc"], [0, "asc"]], // 2=fecha, 5=nombre
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 0 ]}, //fecha
                {targets: [ 2 ], type: 'date-uk', orderData: [ 2, 0 ]},
                { responsivePriority: 1, targets: 7 }
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
                    d.search_puesto = $("#search_puesto").val();
                    d.search_localidad = $("#search_localidad").val();
                    d.search_contrato = $("#search_contrato").val();
                    d.action = "busquedas";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "nro_no_conformidad"},
                {"data" : "fecha_implementacion"},
                {"data" : "nombre"},
                {"data" : "tipo"},
                {"data" : "tipo_accion"},
                {"data" : "responsable_seguimiento"},
                {"data" : "estado"},
                {data: null, defaultContent: '', orderable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_busqueda);
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
                        let user_info = row.user.split('@')[0]+' '+row.created_date;
                        return '<a class="'+permisoAcciones+'" href="#" title="Acciones">'+ //si tiene permiso para ver Acciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="'+permisoVerificaciones+'" href="#" title="Verificaicones">'+ //si tiene permiso para ver Verificaciones
                            '<i class="fas fa-th-list dp_blue"></i>'+
                            '</a>&nbsp;&nbsp;'+
                            '<a class="view" title="Ver" href="#">'+
                            '<i class="far fa-eye dp_blue"></i>'+
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
                            '<a target="_blank" href="#" title="'+user_info+'">'+
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
                <!--<th>Nro. bq.</th>
                <th>Fecha</th>-->
                <th>Nombre</th>
                <th>F. apertura</th>
                <th>F. cierre</th>
                <th>Puesto</th>
                <th>Área</th>
                <th>Contrato</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->busquedas)) {
                foreach ($view->busquedas as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_busqueda']; ?>">
                        <!--<td><?php //echo $rp['id_busqueda']; ?></td>
                        <td><?php //echo $rp['fecha']; ?></td>-->
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['fecha_apertura']; ?></td>
                        <td><?php echo $rp['fecha_cierre']; ?></td>
                        <td><?php echo $rp['puesto']; ?></td>
                        <td><?php echo $rp['area']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td><?php echo $rp['estado']; ?></td>

                        <td class="text-center">
                            <a class="detalles" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>" title="Postulantes"><i class="fas fa-suitcase dp_blue"></i></a>&nbsp;&nbsp;

                            <?php if($rp['cant_uploads']> 0 ){ ?>
                                <a href="#" title="<?php echo $rp['cant_uploads']; ?> adjuntos" >
                                    <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <!--<a class="" href="#" title="renovar">
                                    <i class="far fa-clone"></i>
                                </a>-->
                            <?php } ?>&nbsp;&nbsp;

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">

</div>








