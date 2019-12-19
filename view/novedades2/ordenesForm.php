﻿<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#habilidad').validate({
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



    });

</script>





<!-- Modal -->
<fieldset <?php echo ( PrivilegedUser::dhasAction('HAB_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <?php include_once('view/novedades_partes/orden_detailForm.php');?>





            </div>

            <div class="modal-footer">
                <button class="btn btn-primary " id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



