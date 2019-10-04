﻿<style>

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

        var table = $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/

            "fnInitComplete": function () {
                                $(this).show(); },

            "stateSave": true,
            "order": [[0, "desc"]], // 1=fecha
            /*"columnDefs": [
                { type: 'date-uk', targets: 1 }, //fecha
                { type: 'date-uk', targets: 4 }, //fecha_emision
                { type: 'date-uk', targets: 5 } //fecha_vencimiento
            ]*/
            columnDefs: [
                {targets: [0], type: 'date-uk', orderData: [0]} //fecha
                //{targets: [ 3 ], type: 'date-uk', orderData: [ 3, 6 ]}, //fecha_apertura
                //{targets: [ 4 ], type: 'date-uk', orderData: [ 4, 6 ]} //fecha_cierre
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




        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });




        //$(document).on("click", ".pdf", function(){
        $('.table-responsive').on("click", ".pdf", function(){
            alert('Funcionalidad en contrucción');
            /*params={};
            var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
            params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
            var attr = $('#search_empleado option:selected').attr('id_grupo');
            params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
            //params.id_vencimiento = $("#search_vencimiento").val();
            params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
            params.id_contrato = $("#search_contrato").val();
            params.renovado = $('#search_renovado').prop('checked')? 1 : '';
            params.id_user = <?php //echo $_SESSION['id_user']; ?>
            //var nro_version = Number($('#version').val());
            //var lugar_trabajo = $('#lugar_trabajo').val();
            //var usuario  = "<?php //echo $_SESSION["USER_NOMBRE"].' '.$_SESSION["USER_APELLIDO"]; ?>";
            //var id_cia = "<?php //echo $_SESSION['ID_CIA']; ?>";
            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_renovado="+params.renovado+"&p_id_cia="+params.id_empleado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            var win = window.open(URL, "_blank");*/
            return false;
        });





    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
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
            <tbody>

            <?php if(isset($view->habilitas)) {
                foreach ($view->habilitas as $rp):   ?>
                    <tr data-id="<?php echo $rp['id']; ?>">
                        <td class="<?php echo ($rp['count']> 0 )? 'details-control' : ''; ?>"></td>
                        <td><?php echo $rp['id']; ?></td>
                        <td><?php echo $rp['ot']; ?></td>
                        <td><?php echo $rp['habilita']; ?></td>
                        <td><?php echo $rp['cantidad']; ?></td>
                        <td><?php echo $rp['unitario']; ?></td>
                        <td><?php echo $rp['importe']; ?></td>
                        <td><?php echo $rp['centro']; ?></td>
                        <td><?php echo $rp['certificado']; ?></td>
                        <td><?php echo $rp['periodo']; ?></td>
                        <td><?php echo $rp['fecha']; ?></td>
                        <td><?php echo $rp['count']; ?></td>

                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


        <!--<br/>
        <div class="pull-right pdf">
            <a href="index.php?action="><i class="far fa-file-pdf fa-fw fa-2x dp_blue"></i></a>
        </div>-->

    </div>

</div>

<!--<div class="col-md-1"></div>-->



<!--<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar la renovación?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>-->







