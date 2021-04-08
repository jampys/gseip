<style>

    span.details-control {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }

    span.details-control:before { /* icono de un nodo padre cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f055";
        color: #5fba7d;
    }

    tr.shown span.details-control:before {  /* icono de un nodo padre abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f056";
        color: #DD2C00;
    }

    td.hijo {
        cursor: pointer;
        width: 20px;
    }

    td.hijo:before {  /* icono de un nodo hijo cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f055";
        color: #5fba7d;
    }

    tr.shown td.hijo:before {  /* icono de un nodo hijo abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f056";
        color: #DD2C00;
    }

    td.no-hijo {
        width: 20px;
    }

    td.no-hijo:before {  /* icono de un nodo hijo sin hijos */
        font-family: "Font Awesome 5 Free";
        font-weight: 400;
        content: "\f111";
        color: #01579B;
    }

    span.seleccionable {
        cursor: pointer;
    }


    .highlight { background-color: #a8d1ff !important;
        border-radius: 3px;
        padding-right: 3px;
        padding-left: 3px;
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){

        var tr; //tr es la fila (nodo raiz del arbol)

        //('#example .seleccionable').attr('title','seleccionar');
        $('#example').on('mouseover', '.seleccionable', function(){
            $(this).attr('title','seleccionar');
        });


        /*var table = $('#example').DataTable({
            responsive: true,
            "fnInitComplete": function () {
             $(this).show();
            },
            "stateSave": true,
            columnDefs: [
                { targets: 0, responsivePriority: 1 }, //codigo
                { targets: 1, responsivePriority: 2}, //nombre objetivo
                { targets: 5, width: "90px", responsivePriority: 4}, //progress bar
                { targets: 6, responsivePriority: 3 } //action buttons
            ]

        });*/


        var table = $('#example').DataTable({
            responsive: true,
            deferRender: true,
            //processing: true,
            language: {
                //url: 'libraries/dataTables/extensions/Spanish.json'
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
            },
            'ajax': {
                "type"   : "POST",
                //"url"    : 'index.php?action=ajax_certificados&operation=refreshGrid',
                "url"    : 'index.php',
                //data: {action: "ajax_certificados", operation:"refreshGrid"},
                "data": function ( d ) { //https://datatables.net/reference/option/ajax.data
                    d.search_periodo = $("#search_periodo").val();
                    d.search_puesto = $("#search_puesto").val();
                    d.search_area = $("#search_area").val();
                    d.search_contrato = $("#search_contrato").val();
                    d.search_indicador = $("#search_indicador").val();
                    d.search_responsable_ejecucion = $("#search_responsable_ejecucion").val();
                    d.search_responsable_seguimiento = $("#search_responsable_seguimiento").val();
                    d.todos = $('#search_todos').prop('checked')? 1:0;
                    d.action = "obj_objetivos";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            //rowId: 'id_calibracion',
            'columns': [
                /*{   // Responsive control column
                 data: null,
                 defaultContent: '',
                 className: 'control',
                 orderable: false
                 },*/
                {"data" : "id_objetivo"},
                {"data" : "id_objetivo"},
                {"data" : "puesto"},
                {"data" : "responsable_ejecucion"},
                {"data" : "contrato"},
                {"data" : "id_objetivo"},
                {"data" : "id_objetivo"}
            ],
            //"order": [[ 3, 'desc' ], [ 10, 'desc' ]], //fecha_calibracion, id_calibracion
            /*"columnDefs": [ {
                "targets": 0,
                "render": function ( data, type, row, meta ) {
                    let link = (row.tipo_ensayo == 'N')? 'index.php?action=pdf&operation=certificado&Nro_Serie='+row.nro_serie+'&id_calib='+row.id_calibracion : 'index.php?action=pdf&operation=ppt&Nro_Serie='+row.nro_serie+'&id_calib='+row.id_calibracion
                    return '<a target="_blank" title="descargar certificado" href="'+link+'">'+data+'</a>';
                }
            },
                {
                    "targets": 3,
                    "render": function ( data, type, row, meta ) {
                        return (<?php echo $_SESSION["rol"]; ?> != 3)? row.fecha_calibracion_h : row.fecha_calibracion;
                    },
                    type: 'date-uk'
                },
                {targets: [10], visible: false, sortable: true, searchable: false, type: 'num'} //id_calibracion
            ]*/
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id_objetivo);
                $(row).attr('id_objetivo', data.id_objetivo);
                $(row).attr('cerrado', data.cerrado);
            },
            "columnDefs": [
                {
                    "targets": 0,
                    "render": function (data, type, row, meta) {
                        let hijos = (row.hijos > 0)? 'seleccionable':'';
                        return '<span class="'+hijos+'">'+row.codigo+'</span>';
                    }
                },
                {
                    "targets": 1,
                    "render": function (data, type, row, meta) {
                        let hijos = (row.hijos > 0)? 'details-control':'';
                        return '<span class="'+hijos+'"> '+row.codigo+'</span>&nbsp;'+row.nombre;
                    }
                },
                {
                    "targets": 5,
                    "render": function (data, type, row, meta) {
                        //let hijos = (row.hijos > 0)? 'details-control':'';
                        //return '<span class="'+hijos+'"> '+row.codigo+'</span>&nbsp;'+row.nombre;
                        return '<div class="progress" style="margin-bottom: 0px">'+
                        //'<div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">'+
                        row.progreso+'%'+
                        '</div>'+
                        '</div>';
                    }
                }
            ]

        });



        $('#example').on('click', 'tr td .seleccionable', function() {
            //alert('click');
            var selected = $(this).hasClass("highlight");
            $("tr td .seleccionable").removeClass("highlight");
            if(!selected)
            //alert('pintate');
                $(this).addClass("highlight");
            //alert(tr.attr('data-id'));
            tr.attr('id_objetivo', $(this).closest('tr').attr('data-id'));
        });


        $('#example').on('click', 'span.details-control', function (e) {

            tr = $(this).closest('tr');
            var row = table.row( tr );

            params={};
            params.action = "obj_objetivos";
            params.operation = "getHijos";
            params.id_objetivo = $(this).closest('tr').attr('data-id');

            //alert(params.id_puesto);
            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //alert(Object.keys(data).length);

                    if ( row.child.isShown() ) {
                        //alert('verde');
                        // This row is already open - close it
                        //tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        row.child.hide();
                        tr.removeClass('shown');
                        tr.attr('id_puesto', tr.attr('data-id')); //al cerrar el arbol.
                    }
                    else {
                        // Open this row
                        //alert('rojo');
                        //tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
                        row.child( format(data )).show();
                        tr.addClass('shown');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });

        } );


        $('#example').on('click', 'td.hijo', function (e) {

            var t = $(this).closest('table');
            var tr = $(this).closest('tr');

            params={};
            params.action = "obj_objetivos";
            params.operation = "getHijos";
            params.id_objetivo = $(this).closest('tr').attr('data-id');

            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //alert(Object.keys(data).length);

                    if ( tr.hasClass('shown') ) {
                        //alert('verde');
                        // This row is already open - close it
                        //tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        tr.next('tr').hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        //alert('rojo');
                        //tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
                        tr.after('<tr><td colspan="7">'+format(data)+'</td></tr>').show();
                        tr.addClass('shown');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });

        } );


        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            //https://stackoverflow.com/questions/8749236/create-table-with-jquery-append

            var subTabla ='';

            if(Object.keys(d).length > 0 ){

                var subTabla = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 20px">';

                $.each(d, function(indice, val){
                    //alert('entro al bucle');
                    var clase = (d[indice]['hijos']> 0)? 'hijo' : 'no-hijo';

                    subTabla +=('<tr data-id="'+ d[indice]['id_objetivo']+'">'+
                    '<td class="'+clase+'">'+
                    '<td><span class="seleccionable">'+ d[indice]['codigo']+'</span>&nbsp;'+ d[indice]['nombre']+'</td>'+
                    //'<td>&nbsp;'+ d[indice]['nombre']+'</td>'+
                    '</tr>');
                });

                subTabla +=('</table>');

            }

            return subTabla;

        }



    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Código</th>
                <th>Objetivo</th>
                <th>Puesto</th>
                <th>Resp. ejecución</th>
                <th>Contrato</th>
                <th>a</th>
                <th>b</th>
            </tr>
            </thead>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


