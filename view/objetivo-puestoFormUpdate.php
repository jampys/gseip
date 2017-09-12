<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //se va a utilizar para editar un objetivo desde la grilla principal
        /*$(document).one('click', '#submit',function(){

            var params={};
            params.action = 'habilidad-puesto';
            params.operation = 'saveHabilidadPuesto';
            params.id_habilidad_puesto = $('#id').val();
            params.requerida = $('#requerida').val();

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    $("#myElem").html('Habilidad del puesto puesto guardada con exito').addClass('alert alert-success').show();
                    $("#search").trigger("click");
                }else{
                    $("#myElem").html('Error al guardar la habilidad del puesto').addClass('alert alert-danger').show();
                }
                setTimeout(function() { $("#myElem").hide();
                    $('#myModalUpdate').modal('hide');
                }, 2000);

            });

            return false;

        });*/



    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="habilidad-puesto" id="habilidad-puesto" method="POST" action="index.php">
                    <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php //print $view->habilidadPuesto->getIdHabilidadPuesto(); ?>">

                    <div class="form-group">
                        <label class="control-label" for="objetivo">Objetivo</label>
                        <input class="form-control" type="text" name="objetivo" id="objetivo" value = "<?php //print $view->habilidadPuesto->getPuesto()->getNombre(); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="valor">Valor</label>
                        <input class="form-control" type="text" name="valor" id="valor" value = "<?php //print $view->habilidadPuesto->getPuesto()->getNombre(); ?>" >
                    </div>


                </form>


                <div id="myElem" style="display:none"></div>


            </div>


            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" data-dismiss="modal">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>



