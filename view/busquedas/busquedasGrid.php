<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             },*/
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            //"order": [[3, "asc"], [5, "asc"]], // 3=fecha_apertura, 5=puesto
            "order": [[1, "desc"], [0, "asc"]], // 2=fecha, 5=nombre
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 0 ]}, //fecha
                {targets: [ 2 ], type: 'date-uk', orderData: [ 2, 0 ]},
                { responsivePriority: 1, targets: 7 }
            ]
        });




    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <!--<th>Nro. bq.</th>
                <th>Fecha</th>-->
                <th>Nombre</th>
                <th>F. apertura</th>
                <th>F. cierre</th>
                <th>Puesto</th>
                <th>Área</th>
                <th>Contrato</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->busquedas)) {
                foreach ($view->busquedas as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_busqueda']; ?>">
                        <!--<td><?php //echo $rp['id_busqueda']; ?></td>
                        <td><?php //echo $rp['fecha']; ?></td>-->
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['fecha_apertura']; ?></td>
                        <td><?php echo $rp['fecha_cierre']; ?></td>
                        <td><?php echo $rp['puesto']; ?></td>
                        <td><?php echo $rp['area']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td><?php echo $rp['estado']; ?></td>

                        <td class="text-center">
                            <a class="detalles" href="javascript:void(0);" data-id="<?php echo $puesto['id_puesto'];?>" title="Postulantes"><i class="fas fa-suitcase dp_blue"></i></a>&nbsp;&nbsp;

                            <?php if($rp['cant_uploads']> 0 ){ ?>
                                <a href="#" title="<?php echo $rp['cant_uploads']; ?> adjuntos" >
                                    <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <!--<a class="" href="#" title="renovar">
                                    <i class="far fa-clone"></i>
                                </a>-->
                            <?php } ?>&nbsp;&nbsp;

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">

</div>








