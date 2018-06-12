<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        var jsonPuestos = [];
        var jsonHabilidades = [];
        var jsonRequerida = [];

        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "habilidad-puesto", "operation": "select_requerida"},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {
                jsonRequerida.default= data['default'];
                $.each( data['enum'], function( index, value ){
                    jsonRequerida.push(value);
                });
                //alert(jsonRequerida.default);
                //alert(jsonRequerida[0]);
            }
        });



        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $("#myModal #search_puesto").on('changed.bs.select', function (e) {

            var selected = $("#myModal #search_puesto option:selected");

            item = {};
            item.codigo = selected.attr('codigo');
            item.id_puesto = selected.val();
            item.nombre = selected.text();

            if(jsonPuestos[item.id_puesto]) {
                //alert('el elemento existe');
            }
            else {
                jsonPuestos[item.id_puesto] =item;

                $('#puestos-table tbody').append('<tr data-id='+item.id_puesto+'>' +
                '<td>'+item.codigo+'</td>' +
                '<td>'+item.nombre+'</td>' +
                '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                '</tr>');
            }

        });




        $("#myModal #search_habilidad").on('changed.bs.select', function (e) {

            var selected = $("#myModal #search_habilidad option:selected");

            /*item = {};
            item.id_habilidad = selected.val();
            item.nombre = selected.text();*/

            item = {};
            item.id_habilidad = selected.val();
            item.nombre = selected.text();
            item.requerida = jsonRequerida.default;

            if(jsonHabilidades[item.id_habilidad]) {
                //alert('el elemento existe');
            }
            else {
                jsonHabilidades[item.id_habilidad] =item;

                $('#habilidades-table tbody').append('<tr data-id='+item.id_habilidad+'>' +
                '<td>'+item.nombre+'</td>' +
                '<td>' +
                '<select class="form-control input-sm select_requerida" id="requerida-'+item.id_habilidad+'" name="requerida-'+item.id_habilidad+'">'+
                '</select>'+
                '</td>'+
                '<td class="text-center"><a class="delete" href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                '</tr>');

                $.each(jsonRequerida, function(i, itemx) {
                    $("#requerida-"+item.id_habilidad+"").append('<option value="'+jsonRequerida[i]+'">'+jsonRequerida[i]+'</option>');
                });
                $("#requerida-"+item.id_habilidad+"").val(jsonRequerida.default);

            }



        });


        $('#myModal').on("change", ".select_requerida", function(e){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //alert(id);
            jsonHabilidades[id].requerida = $(this).val();
            //alert(jsonHabilidades[id].requerida);

        });



        $(document).on("click", "#puestos-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonPuestos[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        $(document).on("click", "#habilidades-table .delete", function(e){ //ok
            var index =  $(this).closest('tr').attr('data-id');
            //alert(index);
            $(this).closest('tr').remove(); //elimina la fila de la tabla
            delete jsonHabilidades[index]; //elimina el elemento del array
            e.preventDefault(); //para evitar que suba el foco al eliminar un elemento

        });


        //$(document).one('click', '#myModal #submit',function(){ //ok
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('hizo click');
            //alert(Object.keys(jsonEmpleados).length);
            if (Object.keys(jsonPuestos).length > 0 && Object.keys(jsonHabilidades).length > 0){
                var params={};
                params.action = 'habilidad-puesto';
                params.operation = 'insert';

                var jsonPuestosIx = [];
                for ( var item in jsonPuestos ){
                    jsonPuestosIx.push( jsonPuestos[ item ] );
                }
                var jsonHabilidadesIx = [];
                for ( var item in jsonHabilidades ){
                    jsonHabilidadesIx.push( jsonHabilidades[ item ] );
                }

                params.vPuestos = JSON.stringify(jsonPuestosIx);
                params.vHabilidades = JSON.stringify(jsonHabilidadesIx);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Habilidades de los puestos guardadas con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar las habilidades de los puestos').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            alert('editar etapa');
            //var id = $(this).closest('tr').attr('data-id');
            params={};
            //params.id_postulacion = id;
            params.action = "etapas";
            params.operation = "editEtapa";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });








    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <div class="row">

                        <div class="col-md-6" id="etapas_left_side">




                                <table class="table table-condensed dataTable table-hover">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Etapa</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($view->etapas as $et): ?>
                                        <tr>
                                            <td><?php echo $et['fecha'];?></td>
                                            <td><?php echo $et['etapa'];?></td>
                                            <td class="text-center"><a class="view" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="ver"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
                                            <td class="text-center"><a class="<?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>





                            <!--<table class="table table-condensed dataTable table-hover" id="puestos-table">
                                <thead>
                                <tr>
                                    <th class="col-md-1">Cod.</th>
                                    <th class="col-md-10">Nombre</th>
                                    <th class="col-md-1 text-center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>-->



                        </div>


                        <div class="col-md-6" id="etapas_right_side">







                        </div>


                    </div>


                <div id="myElem" class="msg" style="display:none"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



