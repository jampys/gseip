<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $(document).one('click', '#myModalUpdate #submit',function(){ //ok

            var params={};
            params.action = 'habilidad-puesto';
            params.operation = 'saveHabilidadPuesto';
            params.id_habilidad_puesto = $('#id').val();
            params.requerida = $('#requerida').val();

            $.post('index.php',params,function(data, status, xhr){
                //alert(data);
                //var rta= parseInt(data.charAt(3));
                if(data >=0){
                    $("#myElem").html('Habilidad del puesto puesto guardada con exito').addClass('alert alert-success').show();
                    //$('#content').load('index.php',{action:"habilidades", operation:"refreshGrid"});
                    $("#search").trigger("click");
                    setTimeout(function() { $("#myElem").hide();
                        $('#myModalUpdate').modal('hide');
                    }, 2000);
                }else{
                    $("#myElem").html('Error al guardar la habilidad del puesto').addClass('alert alert-danger').show();
                }

            }, 'json');

            return false;

        });



    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="habilidad-puesto" id="habilidad-puesto" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php print $view->habilidadPuesto->getIdHabilidadPuesto(); ?>">

                    <div class="form-group">
                        <label class="control-label" for="puesto">Puesto</label>
                        <input class="form-control" type="text" name="puesto" id="puesto" value = "<?php print $view->habilidadPuesto->getPuesto()->getNombre(); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="habilidad">Habilidad</label>
                        <input class="form-control" type="text" name="habilidad" id="habilidad" value = "<?php print $view->habilidadPuesto->getHabilidad()->getNombre(); ?>" disabled>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="tipo" >Requerida</label>
                        <select class="form-control" id="requerida" name="requerida">
                            <?php foreach ($view->requerida['enum'] as $req){
                                ?>
                                <option value="<?php echo $req; ?>"
                                    <?php echo ($req == $view->habilidadPuesto->getRequerida())? 'selected' :'' ?>
                                    >
                                    <?php echo $req; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                </form>


                <div id="myElem" class="msg" style="display:none"></div>


            </div>


            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



