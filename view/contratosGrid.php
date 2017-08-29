<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false,
            //modal: true,
            buttons: [
                        {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar($('#confirm').data('id'));
                        },
                        class:"ui-button-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"ui-button-danger"
                    }

                    ]
        });


    });

</script>

<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Contratos</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button class="btn btn-primary btn-sm" type="button" id="new" >Nuevo Contrato</button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nro.</th>
                <th>Companía</th>
                <th>Responsable</th>
                <th>Fecha desde</th>
                <th>Fecha hasta</th>
                <th>Editar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->contratos as $contrato): ?>
                <tr>
                    <td><?php echo $contrato['nro_contrato'];?></td>
                    <td><?php echo $contrato['compania'];?></td>
                    <td><?php echo $contrato['responsable'];?></td>
                    <td><?php echo $contrato['fecha_desde'];?></td>
                    <td><?php echo $contrato['fecha_hasta'];?></td>
                    <td class="text-center"><a class="edit" href="javascript:void(0);" data-id="<?php echo $contrato['id_contrato'];?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>


<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el contrato?
    </div>
</div>



<div id="myElemento" style="display:none">

</div>





