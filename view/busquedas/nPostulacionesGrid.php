<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-postulantes').DataTable({
            responsive: true,
            language: {
                search: '',
                //url: 'resources/libraries/dataTables/Spanish.json',
                emptyTable: 'No existen candidatos para la búsqueda seleccionada'
            },
            sDom:   "<'row'<'col-sm-2'B><'col-sm-4'><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12'>>",
            buttons: [
                {
                    text: '<i class="fas fa-plus fa-fw dp_green"></i>',
                    titleAttr: 'Agregar postulante',
                    attr:  {
                        id: 'add', //https://datatables.net/reference/option/buttons.buttons.attr
                        disabled: function(){
                            let permisoNuevo = '<?php echo (PrivilegedUser::dhasPrivilege('PTN_ABM', array(1)) )? 'false' : 'true' ?>';
                            return (permisoNuevo == 'false')? false : true;
                        }
                    },
                    action: function ( e, dt, node, config ) {
                        //https://datatables.net/reference/option/buttons.buttons.action
                        //usa el evento que esta en nPostulacionesForm.php
                    }
                }
            ],
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            order: [[4, "desc"], [0, "asc"]], // 4=aplica, 0=postulante
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    d.action = "postulaciones2";
                    d.operation = "refreshGrid";
                    d.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
                },
                "dataSrc": ""
            },
            'columns': [
                {"data" : "postulante"},
                {"data" : "etapa"},
                {data: null, defaultContent: ''},
                {data: null, defaultContent: '', orderable: false},
                {data: "aplica", defaultContent: '', orderable: false, visible: false, searchable: false}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_postulacion);
            },
            "columnDefs": [
                {
                    targets: 0, //postulante
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(30)(data, type, row);
                    }
                },
                {
                    targets: 1, //etapa
                    render: function(data, type, row) {
                        return $.fn.dataTable.render.ellipsis(20)(data, type, row);
                    }
                },
                {
                    targets: 2,//aplica
                    width: '10%',
                    responsivePriority: 2,
                    render: function (data, type, row, meta) {
                        let aplica = (row.aplica == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>';
                        return '<a class="etapas" title="Etapas" href="#">'+aplica+'</a>';
                    }
                },
                {
                    targets: 3,//action buttons
                    width: '20%',
                    responsivePriority: 1,
                    render: function (data, type, row, meta) {
                        let permisoNuevo = '<?php echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? 'new' : 'disabled' ?>';
                        let permisoEditar = '<?php echo ( PrivilegedUser::dhasAction('PTN_UPDATE', array(1)) )? 'edit' : 'disabled' ?>';
                        let permisoEliminar = '<?php echo ( PrivilegedUser::dhasPrivilege('PTN_DELETE', array(1)) )? 'delete' : 'disabled' ?>';
                        let user_info = row.user.split('@')[0]+' '+row.fecha;
                        return  '<a class="'+permisoNuevo+'" title="Agregar etapa" href="#">'+
                                    '<i class="fas fa-plus dp_blue"></i>'+
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
                                '<a target="_blank" href="#" title="'+user_info+'">'+
                                    '<i class="fa fa-question-circle dp_light_gray"></i>'+
                                '</a>';
                    }
                }
            ]
        });

        setTimeout(function () {
                    t.columns.adjust();
        },150);




    });

</script>



    
    <div id="empleados-table">
            <table id="table-postulantes" class="table table-condensed table-hover dt-responsive" width="100%">
                <thead>
                <tr>
                    <th>Postulante</th>
                    <th>Ult. etapa</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
            </table>
    </div>

