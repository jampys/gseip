<script type="text/javascript">


    $(document).ready(function(){

        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


