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
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!--<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <img src="resources/img/seip140x40.png" class="img-responsive">
            </div>-->

            <div class="modal-body">
                <img src="resources/img/seip140x40.png" class="img-responsive" width="70" height="20">
                <br/>
                <p class="text-muted"><small>Versión <?php echo $GLOBALS['ini']['application']['app_version'] ?></small></p>
                <p class="text-muted"><small>Copyright &copy; SEIP SRL 2017 Todos los derechos reservados</small></p>
            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


