<style>



</style>



<script type="text/javascript">


    $(document).ready(function(){



        

    });

</script>





                        <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">


                                <div class="alert alert-info">
                                    <strong><?php echo $view->label; ?></strong>
                                </div>

                                <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->
                                <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php //print $view->empleado->getIdParteEmpleado() ?>">







                                <div class="form-group required">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="conductor" name="conductor" <?php //echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Marcar la persona que maneja">Conductor</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="avoid_event" name="avoid_event" <?php //echo ($view->empleado->getAvoidEvent()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Si hay un evento evita el curso definido para éste y calcula conceptos de manera normal">Evitar evento</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="servicio">Comentario</label>
                                    <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario" rows="2"><?php //print $view->empleado->getComentario(); ?></textarea>
                                </div>



                        </form>








