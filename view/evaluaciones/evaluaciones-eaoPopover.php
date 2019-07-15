<div class="row">
    <div class="col-md-3">
        <b>Avance</b>
    </div>
    <div class="col-md-9">

        <div class="progress" style="margin-bottom: 0px">
            <div class="progress-bar progress-bar-striped active <?php echo Soporte::getProgressBarColor($obj['progreso']);?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($obj['progreso'] <= 100)? $obj['progreso']:100; ?>%; min-width: 2em">
                <?php echo $obj['progreso']; ?>%
            </div>
        </div>

    </div>





</div>
<br/>

<div class="row">
    <div class="col-md-3">
        <b>Meta</b>
    </div>
    <div class="col-md-9">
        <p><?php echo $obj['meta']; ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <b>Indicador</b>
    </div>
    <div class="col-md-9">
        <p><?php echo $obj['indicador']; ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <b>Valor</b>
    </div>
    <div class="col-md-9">
        <p><?php echo $obj['meta_valor']; ?></p>
    </div>
</div>
