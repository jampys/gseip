<style>

    td.details-control {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }

    td.details-control:before { /* icono de un nodo padre cerrado */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f055";
        color: #5fba7d;
    }

    tr.shown td.details-control:before {  /* icono de un nodo padre abierto */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f056";
        color: #DD2C00;
    }



</style>

<script type="text/javascript">


    $(document).ready(function(){

        var tr; //tr es la fila (nodo raiz del arbol)

        /*var table = $('#example').DataTable({
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[0, "desc"]], // 1=fecha
            columnDefs: [
                {targets: [0], type: 'date-uk', orderData: [0]} //fecha
            ]
        });*/


        var table = $('#example').DataTable({
            responsive: true,
            language: {
                url: 'resources/libraries/dataTables/Spanish.json'
            },
            "fnInitComplete": function () {
                $(this).show();
            },
            order: [[1, 'desc']], //id
            'ajax': {
                "type"   : "POST",
                "url"    : 'index.php',
                "data": function ( d ) {
                    //d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
                    //d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
                    d.search_busqueda = $('#search_busqueda').val();
                    d.search_input = $('#search_input').val();
                    d.action = "habilitas-control";
                    d.operation = "refreshGrid";
                },
                "dataSrc": ""
            },
            'columns': [
                {data: null, defaultContent: '', orderable: false},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"},
                {"data" : "id"}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id);
            },
            "columnDefs": [
                {
                    targets: 0,
                    createdCell: function(td, cellData, rowData, row, col) {
                        if(rowData.count > 0) $(td).addClass('details-control');
                    },
                    ordenable: false
                }
            ]



        });






        $('#example').on('click', 'td.details-control', function (e) {

            tr = $(this).closest('tr');
            var row = table.row( tr );

            params={};
            params.action = "habilitas-control";
            params.operation = "getHijos";
            params.id = $(this).closest('tr').attr('data-id');

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
                        tr.attr('id', tr.attr('data-id')); //al cerrar el arbol.
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



        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            //https://stackoverflow.com/questions/8749236/create-table-with-jquery-append

            var subTabla ='';

            if(Object.keys(d).length > 0 ){

                subTabla = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 20px">';

                /*subTabla += '<tr style="font-weight: bold; text-align: center">'+
                                '<td>F. parte</td>'+
                                '<td>Nro. parte</td>'+
                                '<td>Cuadrilla</td>'+
                                '<td>Área</td>'+
                                '<td>Período</td>'+
                            '</tr>';*/

                $.each(d, function(indice, val){
                    //alert('entro al bucle');
                    /*subTabla +=('<tr style="text-align:center" data-id="'+ d[indice]['id']+'">'+
                    '<td>'+ d[indice]['fecha_parte']+'</td>'+
                    '<td>'+ d[indice]['nro_parte_diario']+'</td>'+
                    '<td>'+ d[indice]['cuadrilla']+'</td>'+
                    '<td>'+ d[indice]['area']+'</td>'+
                    '<td>'+ d[indice]['periodo']+'</td>'+
                    '</tr>');*/
                    subTabla +=('<tr data-id="'+ d[indice]['id']+'">'+
                    '<td>'+ d[indice]['fecha_parte']+'&nbsp;&nbsp;'+
                            d[indice]['nro_parte_diario']+'&nbsp;&nbsp;'+
                            d[indice]['cuadrilla']+'&nbsp;&nbsp;'+
                            d[indice]['area']+'&nbsp;&nbsp;'+
                            d[indice]['periodo']+
                            '</td>'+
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





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th></th>
                <th>Id</th>
                <th>Nro. OT</th>
                <th>Nro. Habilita</th>
                <th>Cant. Un.</th>
                <th>Pr. Unitario</th>
                <th>Importe</th>
                <th>CC</th>
                <th>Cerfificado</th>
                <th>Período</th>
                <th>Fecha</th>
                <th>Partes</th>
            </tr>
            </thead>
        </table>



    </div>

</div>

<!--<div class="col-md-1"></div>-->












