<script type="text/javascript">


    $(document).ready(function(){


        $(document).on('click', '#culito',function(){
            //alert('toco en submit1');
            //window.location.href = "index.php?action=habilitas&operation=connection";
            $("#txt-form").submit(); // Submit the form
            return false;
        });





    });

</script>

<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Conversores de habilitas</h4>
    <hr class="hr-primary"/>






    <form name ="txt-form" id="txt-form" method="POST" action="index.php?action=habilitas&operation=connection" enctype="multipart/form-data">
            <div class="alert alert-info" role="alert">

                <div class="row">
                    <div class="col-md-9">
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Habilitas YPF Chubut
                    </div>
                    <div class="col-md-3">

                            <label class="btn btn-primary" for="fileToUpload">
                                <input id="fileToUpload" name="fileToUpload" type="file" style="display:none"
                                    onchange="$('#upload-file-info').html(this.files[0].name)">
                                Seleccione archivo
                            </label>

                            <button class="btn btn-primary" id="culito" name="culito" type="submit">Convertir</button>


                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">

                    </div>
                    <div class="col-md-3">
                        <span id="upload-file-info"></span>

                    </div>
                </div>

            </div>
    </form>


    <!--<div class="alert alert-info" role="alert">
                <div class="row">
                    <div class="col-sm-10">
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Muestra los partes, tipos  y  números de órden para un período indicado.
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="submit2" name="submit2" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                    </div>
                </div>
            </div>-->













</div>

<div class="col-md-1"></div>





<div id="myElemento" class="msg" style="display:none">

</div>





