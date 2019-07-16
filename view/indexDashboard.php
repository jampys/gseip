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



        $('#example').on('click', 'td.details-control', function (e) {

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




<div class="row" style="height: 50px">




</div>


<div class="row">

    <div class="col-md-9">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">Mis objetivos</div>
            <div class="panel-body">

                <?php //echo $_SESSION["id_empleado"]   ?>




                <!-- Table -->
                <?php if(isset($view->objetivos) && sizeof($view->objetivos) > 0) { ?>

                <table id="example" class="table table-striped table-condensed table-hover" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Objetivo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php foreach ($view->objetivos as $rp):   ?>
                            <tr data-id="<?php echo $rp['id_objetivo']; ?>"
                                id_objetivo="<?php echo $rp['id_objetivo'];?>"
                                cerrado="<?php echo $rp['cerrado']; ?>"
                                >
                                <td class="details-control"></td>
                                <td><span class="<?php echo ($rp['hijos']> 0 )? 'seleccionable' : ''; ?>"><?php echo $rp['codigo'];?></span></td>
                                <td><?php echo $rp['nombre']; ?></td>
                                <td>
                                    <div class="progress" style="margin-bottom: 0px">
                                        <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($rp['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rp['progreso'] <= 100)? $rp['progreso']:100; ?>%; min-width: 2em">
                                            <?php echo $rp['progreso']; ?>%
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach;  ?>
                    </tbody>
                </table>


                <?php }else{ ?>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen empleados en el puesto seleccionado. Para afectar un empleado a un puesto diríjase a
                        <?php if ( PrivilegedUser::dhasPrivilege('CON_VER', array(1)) ) { ?>
                            <a href="index.php?action=contratos">Contratos</a></p>
                        <?php } ?>
                    </div>

                <?php } ?>











            </div>


        </div>


    </div>


    <div class="col-md-3"></div>











</div>










