<script type="text/javascript">


    $(document).ready(function(){

        $(document).on('click', '#submit1',function(){
            alert('toco en submit1');
            window.location.href = "index.php?action=habilitas&operation=connection";
            return false;
        });

        $(document).on('click', '#culito',function(){
            alert('toco en submit1');
            //window.location.href = "index.php?action=habilitas&operation=connection";
            $("#txt-form").submit(); // Submit the form
            return false;
        });

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

    <h4>Empleados</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button class="btn btn-default" type="button" id="new" <?php echo ( PrivilegedUser::dhasAction('EMP_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Empleado
        </button>
    </div>














            <div class="alert alert-info" role="alert">
                <div class="row">
                    <div class="col-md-8">
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Muestra los partes involucrados para un período, empleado y concepto indicados.
                    </div>
                    <div class="col-md-4">
                        <!--<button class="btn btn-primary" id="submit1" name="submit1" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>-->
                        <form name ="txt-form" id="txt-form" method="POST" action="index.php?action=habilitas&operation=connection" enctype="multipart/form-data">
                            <label class="btn btn-primary" for="fileToUpload">
                                <input id="fileToUpload" name="fileToUpload" type="file" style="display:none"
                                    onchange="$('#upload-file-info').html(this.files[0].name)">
                                Button Text Here
                            </label>
                            <span class='label label-info' id="upload-file-info"></span>
                            <button class="btn btn-primary" id="culito" name="culito" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                        </form>

                    </div>
                </div>
            </div>


            <div class="alert alert-info" role="alert">
                <div class="row">
                    <div class="col-sm-10">
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Muestra los partes, tipos  y  números de órden para un período indicado.
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="submit2" name="submit2" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                    </div>
                </div>
            </div>


            <div class="alert alert-info" role="alert">
                <div class="row">
                    <div class="col-sm-10">
                        <span class="glyphicon glyphicon-tags" ></span>&nbsp Muestra los empleados sin parte ni suceso para un período indicado.
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="submit3" name="submit3" type="submit">&nbsp;<i class="far fa-file-pdf fa-lg"></i>&nbsp;</button>
                    </div>
                </div>
            </div>




        <div id="myElem" style="display:none"></div>









</div>

<div class="col-md-1"></div>





<div id="myElemento" class="msg" style="display:none">

</div>





