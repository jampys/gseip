<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //se va a utilizar para editar un objetivo desde la grilla principal
        $(document).one('click', '#submit',function(){

            if($('#id_objetivo_puesto').val()){

                //alert($('#id_objetivo_puesto').val());

                var params={};
                params.action = 'objetivo-puesto';
                params.operation = 'saveObjetivoPuesto';
                params.id_objetivo_puesto = $('#id_objetivo_puesto').val();
                params.valor = $('#valor').val();

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    if(data >=0){
                        $("#myElem").html('Objetivo del puesto puesto guardado con exito').addClass('alert alert-success').show();
                        $("#search").trigger("click");
                    }else{
                        $("#myElem").html('Error al guardar el objetivo del puesto').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElem").hide();
                                            $('#myModalUpdate').modal('hide');
                                            }, 2000);

                });

                return false;



            }



        });






    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="habilidad-puesto" id="habilidad-puesto" method="POST" action="index.php">
                    <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php //print $view->objetivoPuesto->getIdObjetivo(); ?>">
                    <input type="hidden" name="id_objetivo_puesto" id="id_objetivo_puesto" value="<?php print ($view->objetivoPuesto)? $view->objetivoPuesto->getIdObjetivoPuesto() : ''; ?>">

                    <div class="form-group">
                        <label class="control-label" for="objetivo">Objetivo</label>
                        <input class="form-control" type="text" name="objetivo" id="objetivo" value = "<?php print ($view->objetivoPuesto)? $view->objetivoPuesto->getObjetivo()->getNombre() : ''; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="valor">Valor</label>
                        <input class="form-control" type="text" name="valor" id="valor" value = "<?php print ($view->objetivoPuesto)? $view->objetivoPuesto->getValor() : ''; ?>" >
                    </div>


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



