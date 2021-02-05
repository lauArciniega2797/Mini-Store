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
    // console.log('Colores');
    e.preventDefault();
    var empty = notEmpty();
    if (empty) {
        var datos = new FormData();
        datos.append('name',document.getElementById("inputName").value);
        datos.append('price',document.getElementById("inputPrice").value);
        datos.append('procedence_store',document.getElementById("inputPStore").value);
        datos.append('store_price',document.getElementById("inputSPrice").value);
        datos.append('quantity',document.getElementById("inputQuantity").value);
        datos.append('image', document.getElementById('file').files[0]);
    console.log(datos);

    // EL FORMDATA CON AJAX ME REFRESCA LA PAGINA CUANDO GUARDO UN PRODUCTO Y CUANDO LLEVA UNA IMAGEN
        $.ajax({
            type:'POST',
            data: {formData: datos},
            url:'index.php?page=products&action=saveProducts',
            contentType: false,
            processData: false,
            success:function(response){
                console.log(response);
                // if (response.split('<section class="container">')[1].split('<!-- Si hay')[0].trim() == "correct") {
                //     document.getElementById('successData').style.display = 'block';
                //     document.getElementById('successData').innerHTML = 'Los datos se han subido correctamente';
                //     setTimeout(
                //         function(){
                //             location.href = "http://localhost/Ahlai/products/";
                //         }, 3000
                //     );
                // }
            }
        });
        return false;
    } else {
        document.getElementById('failData').style.display = 'block';
        document.getElementById('failData').innerHTML = 'Datos Incompletos';
    }
})

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