<script type="text/javascript">


    $(document).ready(function(){

        var jsonCompetencias = [];


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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form class="form-horizontal" name ="eac-form" id="eac-form" method="POST" action="index.php">
                    <input type="hidden" name="id_evaluacion_competencia" id="id_evaluacion_competencia" value="<?php print $view->evaluacion_competencia->getIdEvaluacionCompetencia() ?>">
                    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->evaluacion_competencia->getIdEmpleado() ?>">


                    <!--<div class="form-group required">
                        <label for="nro_contrato" class="col-md-4 control-label">Nro. Contrato</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="nro_contrato" id="nro_contrato" placeholder="Nro. Contrato" value = "<?php //print $view->contrato->getNroContrato() ?>">
                        </div>
                    </div>-->


                    <?php foreach ($view->competencias as $com){
                        ?>


                        <div class="form-group required">
                            <label for="" class="col-md-4 control-label"><?php echo $com['nombre']; ?> </label>
                            <div class="col-md-8">
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


                <div id="myElem" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



