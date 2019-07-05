<style>

    td.details-control, td.hijo {
        content: 'A';
        cursor: pointer;
    }
    tr.shown td.details-control {
        content: 'B';
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){

        var table = $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "stateSave": true,
            /*columnDefs: [
             {targets: 1, render: $.fn.dataTable.render.ellipsis( 20)}

             ]*/
            "fnInitComplete": function () {
                $(this).show(); }
        });


        // Add event listener for opening and closing details
        //$('#example tbody').on('click', 'td.details-control', function () {
        $(document).on('click', 'td.details-control', function (e) {


            params={};
            params.action = "puestos";
            params.operation = "getHijos";
            params.id_puesto = $(this).closest('tr').attr('data-id');

            //alert(params.id_puesto);
            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    alert(Object.keys(data).length);

                    //if(Object.keys(data).length > 0){

                        /*$.each(data, function(indice, val){
                            var label = data[indice]["nombre"]+' ('+data[indice]["fecha_desde"]+' - '+data[indice]["fecha_hasta"]+')';
                            $("#id_periodo1, #id_periodo2").append('<option value="'+data[indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data[indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data[indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');

                        });*/


                    //}



                    var tr = $(this).closest('tr');
                    var row = table.row( tr );

                    if ( row.child.isShown() ) {
                        alert('verde');
                        // This row is already open - close it
                        tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        alert('rojo');
                        tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
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

        $(document).on('click', 'td.hijo', function (e) {

            //alert('toco');

            var t = $(this).closest('table');
            var tr = $(this).closest('tr');
            //var row = table.row( tr );
            //tr.after(format(1));


            if ( tr.hasClass('shown') ) {
             alert('verde');
             // This row is already open - close it
             tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
             tr.next('tr').hide();
             tr.removeClass('shown');
             }
             else {
             // Open this row
             alert('rojo');
             tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
             tr.after('<tr><td colspan="7">'+format(1)+'</td></tr>').show();
             tr.addClass('shown');
             }

        } );




    /* Formatting function for row details - modify as you need */
    function format ( d ) {
        // `d` is the original data object for the row
        /*return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 15px">'+
        '<tr>'+
        '<td class="hijo"><i class="fas fa-plus-circle fa-fw"></i></td>'+
        '<td>Full name:</td>'+
        '<td>'+'nombre'+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td class="details-control"><i class="fas fa-plus-circle fa-fw"></i></td>'+
        '<td>Extension number:</td>'+
        '<td>'+'exten'+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td class="details-control"><i class="fas fa-plus-circle fa-fw"></i></td>'+
        '<td>Extra info:</td>'+
        '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
        '</table>';*/

        var tutuca = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 15px">';


        $.each(d, function(indice, val){
            alert('entro al bucle');

            tutuca +=('<tr>'+
            '<td class="hijo"><i class="fas fa-plus-circle fa-fw"></i></td>'+
            '<td>Full name:</td>'+
            '<td>'+'nombre'+'</td>'+
            '</tr>');


         });

        tutuca +=('</table>');

        return tutuca;



    }







        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">

    <h4>Puestos de trabajo</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo ( PrivilegedUser::dhasAction('PUE_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Puesto
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th></th>
                <th>Cod.</th>
                <th>Nombre</th>
                <th>Área</th>
                <th>Nivel competencia</th>
                <th>puesto superior</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->puestos as $puesto):   ?>
                <tr data-id="<?php echo $puesto['id_puesto'];?>">
                    <td class="details-control"></td>
                    <td><?php echo $puesto['codigo'];?></td>
                    <td><?php echo $puesto['nombre'];?></td>
                    <td><?php echo $puesto['area'];?></td>
                    <td><?php echo $puesto['nivel_competencia'];?></td>
                    <td><?php echo $puesto['nombre_superior'];?></td>

                    <td class="text-center">
                        <a class="detalles" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>" title="detalles del puesto"><i class="fas fa-suitcase dp_blue"></i></a>&nbsp;&nbsp;

                        <?php if($puesto['cant_uploads']> 0 ){ ?>
                            <a href="#" title="<?php echo $puesto['cant_uploads']; ?> adjuntos" >
                                <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                            </a>
                        <?php } else{ ?>
                            <a href="#" title="sin adjuntos" class="disabled">
                                <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                            </a>
                        <?php } ?>
                        &nbsp;&nbsp;
                        <a class="view" title="ver" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el puesto de trabajo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








