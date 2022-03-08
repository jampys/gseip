<script type="text/javascript">


    $(document).ready(function(){


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#orden-form #cancel').on('click', function(){
            $('#myModal').modal('hide');
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

                <?php include_once('view/novedades_partes/orden_detailForm.php');?>

            </div>


        </div>
    </div>
</div>




