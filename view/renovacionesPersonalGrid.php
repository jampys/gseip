<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "stateSave": true,
            "order": [[6, "asc"], [7, "asc"] ], //6=priority (oculta), 7=renovacion
            /*"columnDefs": [
                { type: 'date-uk', targets: 1 }, //fecha
                { type: 'date-uk', targets: 4 }, //fecha_emision
                { type: 'date-uk', targets: 5 } //fecha_vencimiento
            ]*/
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 6 ]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 6 ]}, //fecha_emision
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 6 ]}, //fecha_vencimiento
                {targets: [ 6 ], orderData: [ 6]}, //priority
                {targets: [ 7 ], orderData: [ 7]} //renovacion
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });





    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nro. rnv</th>
                <th>Fecha</th>
                <th>vencimiento</th>
                <th>empleado</th>
                <th>F. emisión</th>
                <th>F. vto.</th>
                <th style="display: none">Priority</th>
                <th>Renov.</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($view->renovaciones_personal as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_renovacion']; ?>" style="background-color: <?php echo $rp['color']; ?>" >
                        <td><?php echo $rp['id_renovacion']; ?></td>
                        <td><?php echo $rp['fecha']; ?></td>
                        <td><?php echo $rp['vencimiento']; ?></td>
                        <td><?php echo $rp['empleado']; ?></td>
                        <td><?php echo $rp['fecha_emision']; ?></td>
                        <td><?php echo $rp['fecha_vencimiento']; ?></td>
                        <td style="display: none"><?php echo $rp['priority']; ?></td>
                        <td><?php echo $rp['renovacion']; ?></td>

                        <td class="text-center"><a class="edit" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </a>
                        </td>
                        <td class="text-center"><a class="delete" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
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
        ¿Desea eliminar la renovación?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








