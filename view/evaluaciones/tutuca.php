<div class="row">
    <div class="col-md-3">
        <b>Avance</b>
    </div>
    <div class="col-md-9">

        <div class="progress" style="margin-bottom: 0px">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($com['progreso'] <= 100)? $com['progreso']:100; ?>%; min-width: 2em">
                <?php echo $com['progreso']; ?>%
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
        <p><?php echo $com['meta']; ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <b>Indicador</b>
    </div>
    <div class="col-md-9">
        <p><?php echo $com['indicador']; ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <b>Valor</b>
    </div>
    <div class="col-md-9">
        <p><?php echo $com['meta_valor']; ?></p>
    </div>
</div>
