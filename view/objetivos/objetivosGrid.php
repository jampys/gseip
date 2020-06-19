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


        var table = $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
             $(this).show();
            },
            "stateSave": true,
            columnDefs: [
                { targets: 0, responsivePriority: 1 }, //codigo
                { targets: 1, responsivePriority: 2}, //nombre objetivo
                { targets: 5, width: "90px", responsivePriority: 4}, //progress bar
                { targets: 6, responsivePriority: 3 } //action buttons
                //{targets: 2, render: $.fn.dataTable.render.ellipsis(60)},
                //{targets: 2, render: $.fn.dataTable.render.ellipsis(25)}, //https://datatables.net/blog/2016-02-26

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

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Código</th>
                <th>Objetivo</th>
                <th>Puesto</th>
                <th>Resp. ejecución</th>
                <th>Contrato</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->objetivos)) {
                foreach ($view->objetivos as $rp):  if($rp['id_objetivo_superior'] && $view->todos==0) continue;  ?>
                    <tr data-id="<?php echo $rp['id_objetivo'];  ?>"
                        id_objetivo="<?php echo $rp['id_objetivo'];?>"
                        cerrado="<?php echo $rp['cerrado']; ?>"
                        >
                        <td>

                            <span class="<?php echo ($rp['hijos']> 0 )? 'seleccionable' : ''; ?>">
                                <?php echo $rp['codigo'];?>
                            </span>
                        </td>
                        <td>
                            <span class="<?php echo ($rp['hijos']> 0 )? 'details-control' : ''; ?>"></span>&nbsp;
                            <?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['puesto']; ?></td>
                        <td><?php echo $rp['responsable_ejecucion']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td>
                            <div class="progress" style="margin-bottom: 0px">
                                <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                    <?php echo $rp['progreso']; ?>%
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <!-- si tiene permiso para ver etapas -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'detalle' : 'disabled' ?>" href="javascript:void(0);">
                                <i class="far fa-list-alt fa-fw dp_blue" title="detalle del objetivo"></i>
                            </a>&nbsp;&nbsp;



                            <!-- si tiene permiso para clonar objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? 'clone' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-duplicate dp_blue" title="clonar">
                            </a>&nbsp;&nbsp;



                            <!-- si tiene permiso para ver objetivo -->
                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('OBJ_VER', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;



                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo (  !$rp['cerrado'] &&
                                                    PrivilegedUser::dhasAction('OBJ_UPDATE', array(1))
                                                 )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;



                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo (  !$rp['cerrado'] &&
                                                    PrivilegedUser::dhasAction('OBJ_DELETE', array(1))
                                                 )? 'delete' : 'disabled' ?>" href="javascript:void(0);" title="borrar" >
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>




                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


