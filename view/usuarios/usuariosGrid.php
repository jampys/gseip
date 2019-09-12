<style>

    /* https://stackoverflow.com/questions/20782368/use-font-awesome-icon-as-css-content*/
    /* https://fontawesome.com/how-to-use/on-the-web/advanced/css-pseudo-elements */
    /*https://datatables.net/examples/api/row_details.html*/

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

        //$('#example .seleccionable').attr('title','seleccionar');
        $('#example').on('mouseover', '.seleccionable', function(){
            $(this).attr('title','seleccionar');
        });


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


        $('#example').on('click', 'tr td .seleccionable', function() {
            //alert('click');
            var selected = $(this).hasClass("highlight");
            $("tr td .seleccionable").removeClass("highlight");
            if(!selected)
            //alert('pintate');
                $(this).addClass("highlight");
                //alert(tr.attr('data-id'));
                tr.attr('id_puesto', $(this).closest('tr').attr('data-id'));
        });


        // Add event listener for opening and closing details
        //$('#example tbody').on('click', 'td.details-control', function () {
        $('#example').on('click', 'td.details-control', function (e) {


            tr = $(this).closest('tr');
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

                subTabla +=('<tr data-id="'+ d[indice]['id_puesto']+'">'+
                '<td class="'+clase+'">'+
                '<td><span class="seleccionable">'+ d[indice]['nombre']+'</span></td>'+
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


    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Usuarios</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo ( PrivilegedUser::dhasAction('PUE_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Usuario
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Usuario</th>
                <th>Habilitado</th>
                <th>F. Alta</th>
                <th>F. Baja</th>
                <th>Empleado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->usuarios as $us):   ?>
                <tr data-id="<?php echo $us['id_user'];?>" >
                    <td><?php echo $us['user'];?></td>
                    <td class="text-center"><?php echo ($us['enabled'] == 1)? '<i class="fas fa-check-circle fa-fw dp_green" title="habilitado"></i>' : '<i class="fas fa-ban fa-fw dp_red" title="inhabilitado"></i>'; ?></td>
                    <td><?php echo $us['fecha_alta'];?></td>
                    <td><?php echo $us['fecha_baja'];?></td>
                    <td><?php echo $us['apellido'].' '.$us['nombre'];?></td>

                    <td class="text-center">
                        <a class="roles" href="javascript:void(0);"><i class="far fa-list-alt fa-fw dp_blue" title="Roles"></i></a>&nbsp;&nbsp;
                        <a class="view" title="ver" href="javascript:void(0);"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el usuario?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








