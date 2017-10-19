﻿<style>

    .alert{
        overflow-y:scroll;
        width:100%;
        /*max-height: 50%;*/
    }

</style>

<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        var jsonCompetencias = [];
        var jsonCompetenciasHelp =[];


        //carga un array con la descripcion de los puntajes de cada competencia
        $.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "evaluaciones", "operation": "loadEac_help"},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){

                    /*var id = data[indice]['id_competencia'];
                     item = {};
                     item.id_competencia = id;
                     item.id_puntaje = data[indice]['id_puntaje'];
                     item.descripcion = data[indice]['descripcion'];

                     if(!jsonCompetenciasHelp[id]) {jsonCompetenciasHelp[id]= item; }
                     else {
                     jsonCompetenciasHelp[id].id_puntaje += item.id_puntaje;
                     }*/

                    jsonCompetenciasHelp[indice] = data[indice];
                });

                //alert(Object.keys(jsonCompetenciasHelp).length);
            }

        });



        $('#modalEac').modal({
            backdrop: 'static',
            keyboard: false
        });


        /* validacion del formulario */
        $.validator.addMethod("requerido", $.validator.methods.required, "Seleccione un puntaje");
        jQuery.validator.addClassRules('selectpicker', {
            requerido: true
        });

        $('#eac-form').validate();



        //Al presionar el icono (i) de cada label
        $(document).on("click", ".help_puntaje", function(e){

            var id = $(this).closest('.form-group').find('select').attr('id');
            var label = $(this).closest('.form-group').find('label').text();

            $('#help-box').parent().css("max-height", $("#select-box").height()); //el div padre de #help-box
            $('#help-box').html('<p><span class="glyphicon glyphicon-tags"></span>&nbsp'+label+'</p>')
                          .scrollTop();

            $.each(jsonCompetenciasHelp, function(indice, val){ //carga el array de empleados

                if(jsonCompetenciasHelp[indice]['id_competencia'] == id) {
                    $('#help-box').append('<span class="glyphicon glyphicon-chevron-right"></span>&nbsp')
                    .append('<strong>'+jsonCompetenciasHelp[indice]['id_puntaje']+'</strong>')
                    .append('<p>'+jsonCompetenciasHelp[indice]['descripcion']+'</p>');
                }

            });

        });



        // Al presionar alguno de los select de puntajes
        $('#modalEac').on('change', ".selectpicker", function(e){
            //Solo guarda en el array los elementos que cambiaron, no es necesario tener los que vienen de la BD.
            item = {};
            item.id_evaluacion_competencia = $(this).attr('id_evaluacion_competencia');
            item.id_competencia = $(this).attr('id');
            item.id_puntaje = $(this).val();
            item.id_empleado = $('#id_empleado').val();
            item.periodo = $('#periodo').val();

            if(jsonCompetencias[item.id_competencia]) {
                jsonCompetencias[item.id_competencia].id_puntaje =item.id_puntaje;
                //alert('el elemento existe '+jsonCompetencias[item.id_competencia].id_puntaje);
            }
            else { //si no existe, lo agrega
                jsonCompetencias[item.id_competencia] =item;
                //alert('el elemento No existe '+jsonCompetencias[item.id_competencia].id_puntaje);
            }

        });



        //Al guardar una evaluacion de competencias
        $('#modalEac').on('click', '#submit',function(){
            //alert('guardar evaluacion desempeño');
            //if ($("#eac-form").valid()){
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

                var jsonCompetenciasIx = $.map(jsonCompetencias, function(item){ return item;} );
                params.vCompetencias = JSON.stringify(jsonCompetenciasIx);


                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);
                    //var rta= parseInt(data.charAt(3));
                    if(data >=0){
                        $("#myElem").html('Evaluación de competencias guardada con exito').addClass('alert alert-success').show();

                    }else{
                        $("#myElem").html('Error al guardar evaluación de competencias').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                                            $('#content').load('index.php',{action:"evaluaciones", operation:"refreshGrid"});
                                            $('#modalEac').modal('hide');
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


                    <div class="col-md-7" id="select-box">

                        <form class="form-horizontal" name ="eac-form" id="eac-form" method="POST" action="index.php">
                            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $id_empleado; ?>" >

                            <?php foreach ($view->competencias as $com){ ?>

                                <div class="form-group required">
                                    <label for="" class="col-md-6 control-label"> <?php echo $com['nombre']; ?>   <a href="#"><span class="glyphicon glyphicon-info-sign help_puntaje"></span></a> </label>
                                    <div class="col-md-6">
                                        <select class="form-control selectpicker show-tick" id="<?php echo $com['id_competencia'];?>" name="<?php echo $com['id_competencia'];?>" id_evaluacion_competencia="<?php echo $com['id_evaluacion_competencia'];?>"  >
                                            <option value="" disabled selected>Seleccione el puntaje</option>
                                            <?php foreach ($view->puntajes as $p){ ?>
                                                <option value="<?php echo $p['id_puntaje']; ?>"
                                                    <?php echo ($com['nro_orden'] == $p['nro_orden'])? 'selected' :'' ?>
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
                            <!--<a href="#" class="close" data-dismiss="alert">&times;</a>-->
                            <div id="help-box">
                                Al presionar sobre el ícono <span class="glyphicon glyphicon-info-sign"></span>&nbsp de cada competencia, podrá
                                visualizar la descripción del significado de cada puntaje.
                            </div>
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




