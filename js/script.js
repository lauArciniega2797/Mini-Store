$(document).ready(function(){
if ($('#flexCheckDefault').is(':checked')) {
    $('#inputCreditLimit').removeAttr('disabled');
    $('#inputCreditDays').removeAttr('disabled');
    $("#flexCheckDefault").attr('value', '0');
}
});
var body = document.body, html = document.documentElement;
var total_height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
if (document.getElementById('footer') != null && document.getElementById('header') != null) {
    var footer = document.getElementById('footer').offsetHeight;
    var header = document.getElementById('header') ? document.getElementById('header').offsetHeight : 0;
}

if (document.getElementById('main') != null) {
    document.getElementById('main').style.height = ''+(total_height - (footer+header))+'px';
}

/* ---------- LOGIN USER ----------  */
$('#btn-login').on('click', function(e) {
    e.preventDefault();
    if (validateLogin()) {
        var datos = $('#formulario_ingreso').serialize();

        $.ajax({
            url:'index.php?page=user&action=validateLogin',
            type:'POST',
            data:{data: datos},
            success: function(response){
                console.log(response);
                if(response == 'Correct'){
                    document.getElementById('redirecting').style.display = 'block';
                    document.getElementById('formulario_ingreso').style.display = 'none'
                    setTimeout(
                        function(){
                            location.href = "http://localhost/Mini-Store/?page=dashboard&action=";
                        }, 4000
                    );
                } else {
                    document.getElementById('notification_fail').style.display = 'block';
                    document.getElementById('notification_fail').innerHTML = "Ops! parece que los datos no son correctos, intenta de nuevo.";
                }
            }
        })
    } else {
        // console.log('%c No data found', 'color:red;font-size:3rem;padding:20px 5px;');
        document.getElementById('notification_fail').style.display = "block";
        document.getElementById('notification_fail').innerHTML = "Debes llenar los campos del formulario";
    }
});
/* ---------- INSERT PRODUCT ON DATABASE ----------  */
$('#senData').on('click', function(e){
    // console.log(document.getElementById('file').files[0]);
    e.preventDefault();
    var actionForm = $(this).attr('data');
    var dataID = $(this).attr('data-id');
    if (actionForm != 'deleteProduct') {
        if (notEmpty()) {
            var dataa = new FormData($('form#newProductForm')[0]);
            $.ajax({
                url:'index.php?page=products&action=saveProducts&function='+actionForm+'&id='+dataID,
                type:'POST',
                data: dataa,
                processData: false,
                contentType: false,
                success:function(response){
                    if (response) {
                        $('#successData').html('Los datos se guardaron correctamente');
                        $('#successData').css({'display':'block'});
                    }
                    setTimeout(() => {
                        $('form#newProductForm')[0].reset();
                        $('form#newProductForm').find('input').attr('value','');
                        $('.box-image').css({'background-image':'none'});
                        $('#successData').css({'display':'none'});
                        window.location.href = '?page=products&action=';
                    }, 4000);
                }
            });
        } else {
            document.getElementById('failData').style.display = 'block';
            document.getElementById('failData').innerHTML = 'Datos Incompletos';
        }
    } else {
        $.ajax({
            url:'?page=products&action=saveProducts',
            data: {
                'function': actionForm,
                'id': $(this).attr('href')
            },
            type:'POST',
            success:function(response){
                console.log(response);
                if (response) {
                    $('#successDataModal').html('Se ha eliminado el producto');
                    $('#successDataModal').css({'display':'block'});
                    // return false;
                }
                setTimeout(() => {
                    $('#successDataModal').css({'display':'none'})
                    $('.btn-close').trigger('click');
                    location.reload();
                }, 3000);
            }
        });
    }
})
$('.deleteProductData').on('click', function(e){
    e.preventDefault();
    $.ajax({
        url:$(e.target).attr('data-href'),
        type:'POST',
        dataType:'json',
        success:(response) => {
            // Con esta funcion, puedo recorrer un array que viene de php
            $.each(response, function (indice,valor) {
                $('.modal-footer').find('a#senData').prop('href',valor.id);
                $('.modal-footer').find('a#sendClient').prop('href',valor.id);
                // $('.modal-footer').find('#senData').attr('data-id',valor.id);
                $('#imageProductDelet').attr('src', 'images/'+valor.image);
                $('#imageProductDelet').attr('alt', 'Eunicodin'+valor.name);
                $('#nameProductDelete').html(valor.name);
            })
        }
    })
})

/* ---------- INSERT CLIENT ON DATABASE ----------  */
$('#sendClient').on('click', function(e) {
    e.preventDefault();
    var actionForm = $(this).attr('data');
    var dataID = $(this).attr('data-id');

    console.log($(this));
    console.log($(this).attr('data'));

    console.log('?page=clients&action=saveClient&function='+actionForm+'&id='+dataID);
    if (actionForm != 'deleteClient') {
        $.ajax({
            url:'?page=clients&action=saveClient&function='+actionForm+'&id='+dataID,
            data:$('#newClientForm').serialize(),
            type:'POST',
            success:(response)=>{
                console.log(response);
            }
        })
    } else {
        console.log(actionForm);
        console.log($(this));
        console.log($(this).attr('href'));
        $.ajax({
            url:'?page=clients&action=saveClient',
            data: {
                'function': actionForm,
                'id': $(this).attr('href')
            },
            type:'POST',
            success:function(response){
                console.log(response);
                if (response) {
                    $('#successDataModal').html('Se ha eliminado el producto');
                    $('#successDataModal').css({'display':'block'});
                    // return false;
                }
                setTimeout(() => {
                    $('#successDataModal').css({'display':'none'})
                    $('.btn-close').trigger('click');
                    location.reload();
                }, 3000);
            }
        });
    }
})
$("#flexCheckDefault").on('change', function() {
    if( $(this).is(':checked') ) {
        // Hacer algo si el checkbox ha sido seleccionado
        $('#inputCreditLimit').removeAttr('disabled');
        $('#inputCreditDays').removeAttr('disabled');
        $("#flexCheckDefault").attr('value', '0');
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
        $('#inputCreditLimit').prop('disabled', true);
        $('#inputCreditDays').prop('disabled', true);
        $("#flexCheckDefault").attr('value', '1');
    }
});

// FUNCIONES PARA PRODUCTOS


function validateLogin(){
    var valueUser = document.getElementById('username').value,
        valuePass = document.getElementById('password').value;
        console.log(valuePass, valueUser);
    if (valueUser != "" && valuePass != "") {
        return true;
    }
}
/* ---------- UPLOAD IMAGE ----------  */
function selectImage(){
    var input = document.getElementsByClassName('box-image')[0].nextElementSibling; //con esto seleccionamos el input file
    $(input).trigger('click');//le damos el evento clic al input file (de manera indirecta con el trigger)
    input.addEventListener('change', function(e){ //cuando cambie el valor del input llamara a la funcion showImag
        e.preventDefault();
        showImage(document.getElementById('file'));
    })
}
function showImage(input) {
    if(input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/png') {
        document.getElementById('error-message').style.display = "none";
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementsByClassName('box-image')[0].style.backgroundImage = 'url('+ e.target.result+')';
        }
        reader.readAsDataURL(input.files[0]);
    }else {
        document.getElementById('error-message').innerHTML = "Ingrese solo imagenes con extencion jpg/png";
    }
}
/* ---------- CHECK IF THE FORM'S DATA ARE NOT EMPTY ----------  */
function notEmpty(){
    var flag = true;
    var name = document.getElementById("inputName").value != "" ? document.getElementById("inputName").value.trim() : false;
    var price = document.getElementById("inputPrice").value != "" ? document.getElementById("inputPrice").value.trim() : false;
    var procedence_store = document.getElementById("inputPStore").value != "" ? document.getElementById("inputPStore").value.trim() : false;
    var store_price = document.getElementById("inputSPrice").value != "" ? document.getElementById("inputSPrice").value.trim() : false;
    var quantity = document.getElementById("inputQuantity").value != "" ? document.getElementById("inputQuantity").value.trim() : false;

    if(!(name && price && procedence_store && store_price && quantity)){
        flag = false;
    }

    return flag;
}
function onKeyDownHandler(event) {
    event.stopPropagation()
    var parent = event.target;
    var code = event.keyCode;//event.which || 
    
    //ver si el padre es texto o numero
    if (parent.getAttribute('name') == 'name' || parent.getAttribute('name') == 'procedence_store') {
        if(!((code > 64 && code < 91) || code == 32 || code == 186 || code == 16 || code == 192 || code == 37 || code == 39 || code == 9 || code == 20 || code == 8)){
            return false; //se retorna falso para que no se escriba el caracter que no va
        }
    } else {
        if(!((code > 47 && code < 58) || (code > 95 && code < 106) || code == 190 || code == 110 || code == 16 || code == 37 || code == 39 || code == 9 || code == 8)){
            return false;
        }
    }   
}