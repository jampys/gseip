<style>

    #table-box .table-responsive{
        overflow-x: auto;
        overflow-y: auto;
    }

    #table-box table tr td {
        font-size: 11px !important;
        /*text-align: justify;*/
    }

    #table-box table tr th {
        text-align: center;
    }

    .popover-title {
        font-family: 'Roboto', sans-serif;
        font-size: 13px;
    }
    .popover-content table td {
        font-family: 'Roboto', sans-serif;
        font-size: 13px;
    }


    .modal-dialog{
        width:80%;
    }

    .input-group-addon{
        padding-left: 6px;
        padding-right: 6px;
    }




</style>

<script type="text/javascript">


    $(document).ready(function(){

        $('[data-toggle="popover"]').popover({html:true, placement: "right", container: "#modalEao"});

        /*function verticalTable(){

            $("#table-box table").each(function () { //http://jsfiddle.net/zwdLj/
                var $this = $(this);
                var newrows = [];
                $this.find("tr").each(function () {
                    var i = 0;
                    $(this).find("td,th").each(function () {
                        i++;
                        if (newrows[i] === undefined) {
                            newrows[i] = $("<tr></tr>");
                        }
                        newrows[i].append($(this));
                    });
                });
                $this.find("tr").remove();
                $.each(newrows, function () {
                    $this.append(this);
                });
            });

        }*/



        $('.selectpicker').selectpicker();

        var jsonObjetivos = [];
        //var jsonAspectosGeneralesHelp ={}; //objeto


        //carga un array con la descripcion de los puntajes de cada competencia
        /*$.ajax({
            url:"index.php",
            type:"post",
            data:{"action": "evaluaciones", "operation": "loadEaag_help"},
            dataType:"json",//xml,html,script,json
            success: function(data, textStatus, jqXHR) {

                $.each(data, function(indice, val){

                    if(!jsonAspectosGeneralesHelp[data[indice]['id_aspecto_general']]) {
                        jsonAspectosGeneralesHelp[data[indice]['id_aspecto_general']] = []; //array
                    }

                    jsonAspectosGeneralesHelp[data[indice]['id_aspecto_general']].push(data[indice]);//['descripcion'];
                    //jsonAspectosGeneralesHelp[indice] = data[indice];
                });

                //alert(Object.keys(jsonAspectosGeneralesHelp).length);
                //alert(jsonAspectosGeneralesHelp[1][0]['descripcion']);
            }

        });*/



        $('#modalEao').modal({
            backdrop: 'static',
            keyboard: false
        });


        /* validacion del formulario */
        $.validator.addMethod("cRequired", $.validator.methods.required, "Ingrese la ponderación");
        $.validator.addMethod("cRange", $.validator.methods.range, "Ingrese un valor entre 0 y 100");
        jQuery.validator.addClassRules('ponderacion', {
            cRequired: {
                depends: function(element) { //la ponderacion es requerida solo si cargó el puntaje.
                    return $(this).closest('.fila').find('.selectpicker').val() != '';
                }
            },
            cRange: [0, 100]
        });


        //Al presionar el icono (i) de cada label
        /*$('#modalEaag').on("click", ".help_puntaje", function(e){

            var id = $(this).closest('.form-group').find('select').attr('id');
            var label = jsonAspectosGeneralesHelp[id][0]['nombre'];
            var definicion = jsonAspectosGeneralesHelp[id][0]['definicion'];

            //$('#help-box').css("max-height", $("#select-box").height());
            $('#help-box').css("min-height", '250px');
            //$('#help-box .table-responsive').css("max-height", $("#select-box").height() );

            $('#label-box').html('<p><span class="glyphicon glyphicon-tags"></span> &nbsp; <strong>'+label+'</strong></p>')
                          .append('<p>'+definicion+'</p>');
                          //.scrollTop();


            $('#table-box table').html('');
            $.each(jsonAspectosGeneralesHelp[id], function(indice, val){
                $('#table-box table').append('<tr><th><strong>'+val['puntaje']+'</strong></th>'+val['descripcion']+'</tr>')
                                     .scrollTop();
            });
            //verticalTable();


        });*/



        // Al presionar alguno de los select de puntajes
        $('#modalEao').on('change', ".selectpicker, .ponderacion", function(e){
            //Solo guarda en el array los elementos que cambiaron, no es necesario tener los que vienen de la BD.
            item = {};
            /*item.id_evaluacion_objetivo = $(this).attr('id_evaluacion_objetivo');
            item.id_objetivo = $(this).attr('id');
            item.id_puntaje_objetivo = $(this).val(); */
            item.id_evaluacion_objetivo = $(this).closest('.fila').attr('id_evaluacion_objetivo');
            item.id_objetivo = $(this).closest('.fila').attr('id');
            item.id_puntaje_objetivo = $(this).closest('.fila').find('.selectpicker').val();
            item.id_empleado = $('#id_empleado').val();
            item.id_plan_evaluacion = $('#id_plan_evaluacion').val();
            item.periodo = $('#periodo').val();
            item.ponderacion = $(this).closest('.fila').find('.ponderacion').val();
            //alert('ponderacion: '+item.ponderacion);
            //throw new Error();

            if(jsonObjetivos[item.id_objetivo]) {
                jsonObjetivos[item.id_objetivo].id_puntaje_objetivo = item.id_puntaje_objetivo;
                jsonObjetivos[item.id_objetivo].ponderacion = item.ponderacion;
                //alert('el elemento existe '+jsonObjetivos[item.id_competencia].id_puntaje);
            }
            else { //si no existe, lo agrega
                jsonObjetivos[item.id_objetivo] =item;
                //alert('el elemento No existe '+jsonObjetivos[item.id_competencia].id_puntaje);
            }

        });



        //Al guardar una evaluacion de objetivos
        $('#modalEao').on('click', '#submit',function(){
            //alert('guardar evaluacion objetivos');
            if ($("#eao-form").valid()){
                var params={};
                params.action = 'evaluaciones';
                params.operation = 'saveEao';
                params.periodo = $('#periodo').val();
                params.cerrado = $('#cerrado').val();
                //alert(params.id_compania);

                var jsonObjetivosIx = $.map(jsonObjetivos, function(item){ return item;} );
                params.vObjetivos = JSON.stringify(jsonObjetivosIx);

                //alert('Fin de la prueba');
                //throw new Error();


                $.post('index.php',params,function(data, status, xhr){
                    //No se usa .fail() porque el resultado viene de una transaccion (try catch) que siempre devuelve 1 o -1
                    //alert(xhr.responseText);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Evaluación de objetivos guardada con exito').addClass('alert alert-success').show();
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#modalEao').modal('hide');
                                              }, 2000);

                    }else{
                        $("#myElem").html('Error al guardar evaluación de objetivos').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });





    });

</script>





<!-- Modal -->
<fieldset <?php echo ($view->params['cerrado'] || sizeof($view->objetivos)== 0)? 'disabled': ''; //echo ( PrivilegedUser::dhasAction('PUE_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="modalEao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <?php if(isset($view->objetivos) && sizeof($view->objetivos) > 0) {?>

                <div class="row">

                    <div class="col-md-7" id="select-box">

                        <form class="form-horizontal" name ="eao-form" id="eao-form" method="POST" action="index.php">
                            <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->params['id_empleado']; ?>" >
                            <input type="hidden" name="id_plan_evaluacion" id="id_plan_evaluacion" value="<?php print $view->params['id_plan_evaluacion']; ?>" >
                            <input type="hidden" name="periodo" id="periodo" value="<?php print $view->params['periodo']; ?>" >
                            <input type="hidden" name="cerrado" id="cerrado" value="<?php print $view->params['cerrado']; ?>" >



                            <?php foreach ($view->objetivos as $obj){ ?>

                                <div class="fila" id="<?php echo $obj['id_objetivo'];?>" name="<?php echo $obj['id_objetivo'];?>" id_evaluacion_objetivo="<?php echo $obj['id_evaluacion_objetivo'];?>">

                                    <div class="col-md-8">

                                        <div class="form-group">
                                            <p><strong><?php echo $obj['codigo'];?></strong>&nbsp;<?php echo $obj['nombre']; ?>
                                            <a href="#" tabindex="0" data-toggle="popover" data-trigger="focus" title="Información adicional"
                                               data-content='<?php require('evaluaciones-eaoPopover.php'); ?>'>ver mas</a></p>
                                        </div>

                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <select class="form-control selectpicker show-tick" title="-" data-live-search="true" data-size="5">
                                                <?php foreach ($view->puntajes as $p){ ?>
                                                    <option value="<?php echo $p['id_puntaje_objetivo']; ?>"
                                                        <?php echo ($obj['puntaje'] == $p['puntaje'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $p['puntaje'];?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                            <div class="input-group-addon" style="background-color: #ffffff">
                                                <a href="#" tabindex="0" data-toggle="popover" data-trigger="hover"
                                                   data-content='<?php echo "<b>".$obj['user']."</b> ".$obj['fecha'];  ?>'>?</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input class="form-control ponderacion" type="text" name="ponderacion_<?php print $obj['id_objetivo']; ?>" value ="<?php print $obj['ponderacion']; ?>" >
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>


                                </div>

                            <?php  } ?>


                        </form>


                    </div>


                    <div class="col-md-5" id="help-box">


                            <!--<a href="#" class="close" data-dismiss="alert">&times;</a>-->
                            <div id="label-box" class="alert alert-info fade in">
                                <i class="fas fa-info-circle fa-fw"></i>&nbsp Aquí abajo se podrá
                                visualizar la descripción del significado de cada puntaje.
                            </div>

                            <div id="table-box">
                                <div class="table-responsive">

                                    <table class="table table-condensed dataTable table-hover">

                                        <?php foreach ($view->puntajes as $p){ ?>
                                            <tr>
                                                <td><b><?php echo $p['puntaje']; ?></b></td>
                                                <td><?php echo $p['descripcion']; ?></td>
                                            </tr>
                                        <?php } ?>

                                    </table>

                                </div>

                            </div>


                    </div>

                </div> <!-- row -->

                <?php }else{ ?>
                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El empleado seleccionado no tiene fijados objetivos para el periodo en cuestión.
                    </div>
                <?php } ?>


                <div id="myElem" style="display:none"></div>


            </div> <!-- modal body -->

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>




