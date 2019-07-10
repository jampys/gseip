<style>

    td.details-control {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }

    td.hijo {
        cursor: pointer;
        width: 20px;
    }

    td.no-hijo {
        width: 20px;
    }


    tr.shown td.details-control {
    }

    .highlight { background-color: #a8d1ff !important;
                 border-radius: 3px;
                 padding-right: 3px;
                 padding-left: 3px;
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


        $(document).on('click', 'tr td .pija', function() {
            //alert('click');
            var selected = $(this).hasClass("highlight");
            $("tr td .pija").removeClass("highlight");
            if(!selected)
            //alert('pintate');
                $(this).addClass("highlight");
        });


        // Add event listener for opening and closing details
        //$('#example tbody').on('click', 'td.details-control', function () {
        $(document).on('click', 'td.details-control', function (e) {


            var tr = $(this).closest('tr');
            var row = table.row( tr );

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

                    //alert(Object.keys(data).length);

                    if ( row.child.isShown() ) {
                        //alert('verde');
                        // This row is already open - close it
                        tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        //alert('rojo');
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

            var t = $(this).closest('table');
            var tr = $(this).closest('tr');

            params={};
            params.action = "puestos";
            params.operation = "getHijos";
            params.id_puesto = $(this).closest('tr').attr('data-id');

            $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //alert(Object.keys(data).length);

                    if ( tr.hasClass('shown') ) {
                        alert('verde');
                        // This row is already open - close it
                        tr.find('td').eq(0).html('<i class="fas fa-plus-circle fa-fw"></i>').removeClass('dp_red').addClass('dp_green');
                        tr.next('tr').hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        //alert('rojo');
                        tr.find('td').eq(0).html('<i class="fas fa-minus-circle fa-fw"></i>').removeClass('dp_green').addClass('dp_red');
                        tr.after('<tr><td colspan="7">'+format(data)+'</td></tr>').show();
                        tr.addClass('shown');
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });





















            //alert('toco');


            //var row = table.row( tr );
            //tr.after(format(1));




        } );




    /* Formatting function for row details - modify as you need */
    function format ( d ) {
        //https://stackoverflow.com/questions/8749236/create-table-with-jquery-append

        var tutuca ='';

        if(Object.keys(d).length > 0 ){

            var tutuca = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-left: 20px">';

            $.each(d, function(indice, val){
                //alert('entro al bucle');

                var clase = '';
                var icon = '';

                if (d[indice]['hijos']> 0){
                    //alert('tiene hios');
                    clase = 'hijo';
                    icon = '<i class="fas fa-plus-circle fa-fw dp_green"></i></td>';
                }else{
                        clase = 'no-hijo';
                        icon = '<i class="far fa-circle fa-fw dp_blue"></i></td>';
                    }
                tutuca +=('<tr data-id="'+ d[indice]['id_puesto']+'">'+
                '<td class="'+clase+'">'+icon+
                '<td><span class="pija" style="cursor: pointer">'+ d[indice]['nombre']+'</span></td>'+
                '</tr>');
            });

            tutuca +=('</table>');


        }

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








