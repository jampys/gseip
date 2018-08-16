<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true
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
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Puesto</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->evaluaciones as $evaluacion):   ?>
                <tr id_empleado="<?php echo $evaluacion['id_empleado'];?>"
                    id_plan_evaluacion="<?php echo $evaluacion['id_plan_evaluacion'];?>"
                    >
                    <td><?php echo $evaluacion['apellido'];?></td>
                    <td><?php echo $evaluacion['nombre'];?></td>
                    <td><?php echo $evaluacion['contrato'];?></td>
                    <td><?php echo $evaluacion['puesto'];?></td>
                    <td class="text-center"><a class="loadEac" href="javascript:void(0);" title="Evaluación competencias" ><span class="glyphicon glyphicon-edit <?php echo (!$evaluacion['hasAnyEac'])? 'text-danger': '';  ?>" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="loadEao" href="javascript:void(0);" title="Evaluación objetivos" ><span class="glyphicon glyphicon-edit <?php echo (!$evaluacion['hasAnyEao'])? 'text-danger': '';  ?>" aria-hidden="true"></span></a></td>
                    <td class="text-center"><a class="delete" href="javascript:void(0);" title="Borrar" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








