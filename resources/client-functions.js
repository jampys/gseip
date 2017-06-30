/**
 * Created with JetBrains PhpStorm.
 * User: Titan
 * Date: 25/06/17
 * Time: 21:33
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){

    //alert('client-functions');




    $('#popupbox').dialog({
        autoOpen:false
    });


    //añado la posibilidad de editar al presionar sobre edit
    $(document).on('click', '.edit', function(){
        //this = es el elemento sobre el que se hizo click en este caso el link
        //obtengo el id que guardamos en data-id
        var id=$(this).attr('data-id');
        //preparo los parametros
        params={};
        params.id=id;
        params.action="editClient";
        $('#popupbox').load('index.php', params,function(){
            $('#popupbox').dialog({title:"Editar cliente"}).dialog('open');
        })

    });





    $(document).on('click', '#new', function(){
        params={};
        params.action="newClient";
        $('#popupbox').load('index.php', params,function(){
            $('#popupbox').dialog({title:"Nuevo cliente"}).dialog('open');
        })
    });


    $(document).on('click', '#submit',function(){
        if ($("#client").valid()){
            var params={};
            params.action='saveClient';
            params.id=$('#id').val();
            params.nombre=$('#nombre').val();
            params.apellido=$('#apellido').val();
            params.fecha=$('#fecha').val();
            params.peso=$('#peso').val();
            $.post('index.php',params,function(data, status, xhr){

                //alert(data);
                var rta= parseInt(data.charAt(3));
                //alert(rta);
                if(rta >=0){
                    $("#myElem").html('Cliente guardado con exito').addClass('alert alert-success').show();
                    $('#content').load('index.php',{action:"refreshGrid"});
                }else{
                    $("#myElem").html('Error al guardar el cliente').addClass('alert alert-danger').show();
                }
                setTimeout(function() { $("#myElem").hide();
                    $('#popupbox').dialog('close');}, 5000);

            });

        }
        return false;
    });


    // boton cancelar, uso live en lugar de bind para que tome cualquier boton
    // nuevo que pueda aparecer
    $(document).on('click', '#cancel',function(){
        $('#popupbox').dialog('close');
    });



    $('#confirm').dialog({
        autoOpen: false,
        //modal: true,
        buttons: /*{
         Yes: function () {
         //doFunctionForYes();
         $.fn.borrar($('#confirm').data('id'));
         $(this).dialog("close");
         },
         No: function () {
         //doFunctionForNo();
         $(this).dialog("close");
         }
         }*/
            [
                {
                    text: "Aceptar",
                    click: function() {
                        $.fn.borrar($('#confirm').data('id'));
                        $(this).dialog("close");
                    },
                    "class":"ui-button-danger"
                },
                {
                    text: "Cancelar",
                    click: function() {
                        $(this).dialog("close");
                    },
                    "class":"ui-button-danger"
                }

            ]
    });


    /*$(document).on('click', '.delete', function(){
        //obtengo el id que guardamos en data-id
        var id=$(this).attr('data-id');
        //preparo los parametros
        params={};
        params.id=id;
        params.action="deleteClient";
        $('#popupbox').load('index.php', params,function(){
            $('#content').load('index.php',{action:"refreshGrid"});
        })

    });*/


    $(document).on('click', '.delete', function(){
        //obtengo el id que guardamos en data-id

        //$('#confirm').dialog('open');
        $("#confirm").data('id', $(this).attr('data-id')).dialog("open");
        //return false;

        /*var id=$(this).attr('data-id');
         //preparo los parametros
         params={};
         params.id=id;
         params.action="deleteClient";
         $('#popupbox').load('index.php', params,function(){
         $('#content').load('index.php',{action:"refreshGrid"});
         })*/
        //$('#confirm').dialog('open');
        /*var $form = $(this).closest('form');
         e.preventDefault();
         $('#confirm').modal({
         backdrop: 'static',
         keyboard: false
         })
         .one('click', '#delete', function(e) {
         $form.trigger('submit');
         });*/

        $.fn.borrar = function(id) {
            alert(id);
            //alert('hello world');
            //return this;
            /*var id=$(this).attr('data-id');

             //preparo los parametros
             params={};
             params.id=id;
             params.action="deleteClient";
             $('#popupbox').load('index.php', params,function(){
             $('#content').load('index.php',{action:"refreshGrid"});
             })*/
        };





    });

});


