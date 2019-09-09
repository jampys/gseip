<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "fnInitComplete": function () {
                $(this).show(); },
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
        <button class="btn btn-default" type="button" id="new" <?php echo ( PrivilegedUser::dhasAction('CON_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Contrato
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro.</th>
                <th>Nombre</th>
                <th>Compañía</th>
                <th>Responsable</th>
                <th>Fecha desde</th>
                <th>Fecha hasta</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->contratos as $contrato): ?>
                <tr data-id="<?php echo $contrato['id_contrato'];?>">
                    <td><?php echo $contrato['nro_contrato'];?></td>
                    <td><?php echo $contrato['nombre'];?></td>
                    <td><?php echo $contrato['compania'];?></td>
                    <td><?php echo $contrato['responsable'];?></td>
                    <td><?php echo $contrato['fecha_desde'];?></td>
                    <td><?php echo $contrato['fecha_hasta'];?></td>
                    <td class="text-center">
                        <a class="<?php echo ( PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? 'vehiculos' : 'disabled' ?>" href="javascript:void(0);">
                            <i class="fas fa-car fa-fw dp_blue" title="vehículos"></i>
                        </a>&nbsp;&nbsp;
                        <a class="view" title="ver" href="javascript:void(0);" data-id="<?php echo $contrato['id_contrato'];?>"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo ( PrivilegedUser::dhasAction('CON_UPDATE', explode(',',$contrato['id_domain'])  ) )? 'edit' : 'disabled' ?>" title="editar" href="javascript:void(0);" data-id="<?php echo $contrato['id_contrato'];?>"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>
                    </td>
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



<div id="myElemento" class="msg" style="display:none">

</div>





