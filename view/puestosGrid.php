<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            /*columnDefs: [
                        {targets: 1, render: $.fn.dataTable.render.ellipsis( 20)}

                        ]*/
            "fnInitComplete": function () {
                $(this).show(); }
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">

    <h4>Puestos de trabajo</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default btn-sm" <?php echo ( PrivilegedUser::dhasAction('PUE_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Puesto
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Cod.</th>
                <th>Nombre</th>
                <th>Área</th>
                <th>Nivel competencia</th>
                <th>puesto superior</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->puestos as $puesto):   ?>
                <tr>
                    <td><?php echo $puesto['codigo'];?></td>
                    <td><?php echo $puesto['nombre'];?></td>
                    <td><?php echo $puesto['area'];?></td>
                    <td><?php echo $puesto['nivel_competencia'];?></td>
                    <td><?php echo $puesto['nombre_superior'];?></td>

                    <td class="text-center">
                        <a class="detalles" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>" title="detalles del puesto"><i class="fas fa-suitcase dp_brown"></i></a>&nbsp;&nbsp;

                        <?php if($puesto['cant_uploads']> 0 ){ ?>
                            <a href="#" title="<?php echo $puesto['cant_uploads']; ?> adjuntos" >
                                <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                            </a>
                        <?php } else{ ?>
                            <a href="#" title="sin adjuntos" class="disabled">
                                <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                            </a>
                        <?php } ?>
                        &nbsp;&nbsp;
                        <a class="view" title="ver" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-edit dp_yellow" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasAction('PUE_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el puesto de trabajo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








