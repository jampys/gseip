<style>

    .alert{
        overflow-y:scroll;
        width:100%;
        height: 50%;
    }


</style>

<script type="text/javascript">


    $(document).ready(function(){

        var jsonCompetencias = [];
        var jsonCompetenciasHelp =[];



        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "evaluaciones", "operation": "loadEac_help"},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){ //carga el array de empleados

                    /*var id = data[indice]['id_competencia'];
                    item = {};
                    item.id_competencia = id;
                    item.id_puntaje = data[indice]['id_puntaje'];
                    item.descripcion = data[indice]['descripcion'];

                    if(!jsonCompetenciasHelp[id]) {jsonCompetenciasHelp[id]= item; }
                    else {
                        jsonCompetenciasHelp[id].id_puntaje += item.id_puntaje;
                    }*/

                    jsonCompetenciasHelp = data[indice];


                });

                //alert(jsonCompetenciasHelp[1].id_puntaje);

            }

        });






        $('#modalEac').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#eac-form').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 3
                },
                nombre: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos"
                },
                nombre: "Ingrese el nombre"
            }

        });

        // Al presionar alguno de los select de puntajes
        $(document).on("change", ".select_puntaje", function(e){
            //Solo guarda en el array los elementos que cambiaron, no es necesario tener los que vienen de la BD.
            item = {};
            item.id_evaluacion_competencia = $('#id_evaluacion_competencia').val();
            item.id_competencia = $(this).attr('id');
            item.id_puntaje = $(this).val();
            item.id_empleado = $('#id_empleado').val();
            item.periodo = $('#periodo').val();

            if(jsonCompetencias[item.id_competencia]) {
                jsonCompetencias[item.id_competencia].id_puntaje =item.id_puntaje;
                alert('el elemento existe '+jsonCompetencias[item.id_competencia].id_puntaje);
            }
            else { //si no existe, lo agrega
                jsonCompetencias[item.id_competencia] =item;
                alert('el elemento No existe '+jsonCompetencias[item.id_competencia].id_puntaje);

            }

        });



        //Al guardar una evaluacion de competencias
        $('#modalEac').on('click', '#submit',function(){
            alert('guardar evaluacion desempeño');
            //if ($("#contrato-form").valid()){
                var params={};
                params.action = 'evaluaciones';
                params.operation = 'saveEac';
                /*params.id_contrato=$('#id_contrato').val();
                params.nro_contrato=$('#nro_contrato').val();
                params.fecha_desde=$('#fecha_desde').val();
                params.fecha_hasta=$('#fecha_hasta').val();
                params.id_responsable=$('#id_responsable').val();
                params.id_compania=$('#compania').val();
                alert(params.id_compania); */

                var jsonCompetenciasIx = [];
                for ( var item in jsonCompetencias ){
                    jsonCompetenciasIx.push( jsonCompetencias[ item ] );
                }
                params.vCompetencias = JSON.stringify(jsonCompetenciasIx);


                $.post('index.php',params,function(data, status, xhr){

                    alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    if(data >=0){
                        $("#myElem").html('Evalucion competencias guardada con exito').addClass('alert alert-success').show();

                    }else{
                        $("#myElem").html('Error al guardar evaluacion de competencias').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                                            $('#content').load('index.php',{action:"evaluaciones", operation:"refreshGrid"});
                    }, 2000);

                });

            //}
            return false;
        });





    });

</script>





<!-- Modal -->
<div class="modal fade" id="modalEac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <div class="row">


                    <div class="col-md-7">

                        <form class="form-horizontal" name ="eac-form" id="eac-form" method="POST" action="index.php">
                            <input type="hidden" name="id_evaluacion_competencia" id="id_evaluacion_competencia" value="<?php print $view->evaluacion_competencia->getIdEvaluacionCompetencia() ?>">
                            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->evaluacion_competencia->getIdEmpleado() ?>">


                            <?php foreach ($view->competencias as $com){
                                ?>

                                <div class="form-group required">
                                    <label for="" class="col-md-6 control-label"><?php echo $com['nombre']; ?> </label>
                                    <div class="col-md-6">
                                        <select class="form-control select_puntaje" id="<?php echo $com['id_competencia'];?>" name="<?php echo $com['id_competencia'];?>" >
                                            <option value="" disabled selected>Seleccione el puntaje</option>
                                            <?php foreach ($view->puntajes as $p){ ?>
                                                <option value="<?php echo $p['id_puntaje']; ?>"
                                                    <?php echo ($view->evaluacion_competencia->getIdEvaluacionCompetencia() && $com['nro_orden'] == $p['nro_orden'])? 'selected' :'' ?>
                                                    >
                                                    <?php echo $p['nro_orden'];?>
                                                </option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                </div>



                            <?php  } ?>


                        </form>


                    </div>


                    <div class="col-md-5">


                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <span class="glyphicon glyphicon-tags" ></span>&nbsp Panel Content
                            Puede seleccionar mas de un proceso porque la seleccion es posible que sea
                            dificiel de soportar y es por eso.
                            Que los costos financieros sean contra indicados.
                            El vocero del gobierno de Emmanuel Macron, Christophe Castane, y el líder de Francia Insumisa, Jean-Luc Melenchon, estaban entre los objetivos del grupo. Todos los detenidos tienen entre 17 y 25 años y están siendo investigados por la justicia.
                            También es una composición de caracteres imprimibles (con grafema) generados por un algoritmo de cifrado que, aunque no tienen sentido para cualquier persona, sí puede ser descifrado por su destinatario original. En otras palabras, un texto es un entramado de signos con una intención comunicativa que adquiere sentido en determinado contexto.

                            Las ideas esenciales que comunica un texto están contenidas en lo que se suele denominar «macroproposiciones», unidades estru
                        </div>




                    </div>









                </div> <!-- row -->


                <div id="myElem" style="display:none"></div>


            </div> <!-- modal body -->

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



