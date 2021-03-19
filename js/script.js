$(document).ready(function(){
    if ($('#flexCheckDefault').is(':checked')) {
        $('#inputCreditLimit').removeAttr('disabled');
        $('#inputCreditDays').removeAttr('disabled');
        $("#flexCheckDefault").attr('value', '0');
    }

    $.ajax({
        url:'?page=sales&action=showSales',
        success:(response)=>{
            $('#table_sales').html(response);
        }
    });
    getDataClient();
    changePayMethod();
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
    // e.preventDefault();
    $.ajax({
        url:$(e.target).attr('data-href'),
        type:'POST',
        dataType:'json',
        success:(response) => {
            // Con esta funcion, puedo recorrer un array que viene de php
            $.each(response, function (indice,valor) {
                $('.modal-footer').find('a#senData').prop('href',valor.id);
                $('.modal-footer').find('a#sendClient').prop('href',valor.id);
                $('.modal-footer').find('a#sendProvider').prop('href',valor.id);
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
                    $('#successDataModal').html('Se ha eliminado el cliente');
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
/* ---------- INSERT PROVIDER ON DATABASE ----------  */
$('#senDataProvider').on('click', function(e) {
    e.preventDefault();
    var actionForm = $(this).attr('data');
    var dataID = $(this).attr('data-id');

    console.log($(this));
    console.log($(this).attr('data'));

    console.log('?page=providers&action=saveProvider&function='+actionForm+'&id='+dataID);
    if (actionForm != 'deleteProvider') {
        $.ajax({
            url:'?page=providers&action=saveProvider&function='+actionForm+'&id='+dataID,
            data:$('#newProviderForm').serialize(),
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
            url:'?page=providers&action=saveProvider',
            data: {
                'function': actionForm,
                'id': $(this).attr('href')
            },
            type:'POST',
            success:function(response){
                console.log(response);
                if (response) {
                    $('#successDataModal').html('Se ha eliminado el proveedor');
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

/* ---------- FUNCIONES PARA VENTAS ----------  */
$('#selectClient').on('change', function(e){
    getDataClient();
})
$('#payMethod').on('change', function(){
    changePayMethod();
})
// agregar productos a la tabla de la venta(Validado)
filter_array = [];
$('.addProduct').on('click', function(e){
    e.preventDefault();
    var idProduct = $("#selectProduct option:selected").attr('value');
    var cant = $('#inputCantidadProduct').val();

    $.ajax({
        url:'?page=sales&action=addProductToCar',
        type:'POST',
        dataType:'json',
        data:{id:idProduct, cantidad:cant},
        success:(response) => {
            //FILTRO #1
            console.log(response);
            filter_array.push(response);
            let errorMessage = false;
            /*for (let i = 0; i < filter_array.length; i++) { //-------------NO BORRAR!!!! este es como el de abajo pero sin el forEach. A la antigua :v
                if (array_product.length <= 0) {
                    array_product.push(filter_array[i]);
                } else {
                    // let indexOf = array_product.findIndex((product) => product.id === filter_array[i].id); // (product) es el parametro que le estoy pasando a FindIndex. El parametro es el array_product
                    let indexOf;
                    for (let j = 0; j < array_product.length; j++) {
                        if (array_product[j].id === filter_array[i].id) {
                            indexOf = j;
                            break;
                        }
                    }
                    if (indexOf > -1){
                        array_product[indexOf].Cantidad += filter_array[i].Cantidad;
                        array_product[indexOf].Total = array_product[indexOf].Cantidad * array_product[indexOf].Precio;
                    } else {
                        array_product.push(filter_array[i]);
                    }
                }
            }*/
            filter_array.forEach(mov => {
                if (array_product.length <= 0) {
                    mov.Cantidad <= mov.Disponibles ? array_product.push(mov) : errorMessage = true;
                } else {
                    let productIndex = array_product.findIndex(product => product.id === mov.id);
                    if (productIndex > -1) {
                        array_product[productIndex].Cantidad + mov.Cantidad <= array_product[productIndex].Disponibles ? array_product[productIndex].Cantidad += mov.Cantidad : errorMessage = true;
                        array_product[productIndex].Total = array_product[productIndex].Cantidad * array_product[productIndex].Precio;
                    } else {
                        mov.Cantidad <= mov.Disponibles ? array_product.push(mov) : errorMessage = true;
                    }
                }
            });
            if (errorMessage) {
                errorMessage = "No se puede agregar el producto, la cantidad de productos es mayor a la cantidad en stock.";
                alert(errorMessage);
            }
            filter_array = [];
            let string = '', total = 0; $('#CarProduct').html("");
            array_product.forEach((product,index) => {
                string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>"+product.Total+"</td><td><a class='btn btn-danger addProduct' onclick='deleteFromCar("+index+","+product.Total+")'>Eliminar</a></td></tr>";
                total += product.Total;
                $('#CarProduct').html(string);
            })
            $('#subtotal').html(total)
            CalcTotal();
        }
    })
})
// Detecta cual producto se le esta cambiando la cantidad
$(document).on('change','.editCant', function(e){
    let limit = $(e.target).attr('max');
    let id = $(e.target).attr('data-id');
    if (parseInt($(e.target).val()) > parseInt(limit)){
        alert('no hay suficientes productos en stock');
        $(e.target).val(limit);
    }
    array_product.forEach(product =>{
        if (product.id == id) {
            product.Cantidad = parseInt($(e.target).val());
            product.Total = product.Cantidad * product.Precio;
        }
    })
    let string = '', total=0; $('#CarProduct').html("");
    array_product.forEach((product,index) => {
        string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>"+product.Total+"</td><td><a class='btn btn-danger addProduct' onclick='deleteFromCar("+index+")'>Eliminar</a></td></tr>";
        total += product.Total;
        $('#CarProduct').html(string);
    })
    $('#subtotal').html(total);
    CalcTotal();
})
/*
    Al enviar la venta, se tiene que hacer por cada producto.
    • En cliente se debe actulizar el credito nuevo (esto se debe hacer una sola vez. LA venta solo tiene un cliente)
    • En producto se debe reducir el stock (por cada producto en array producto se debe reducir el stock)
    • Se debe enviar el id del cliente, el id del producto, un folio aleatorio, el metodo de pago(pagado, fiado),fecha de la venta, el subtotal y el total
*/
$('#senDataSale').on('click', function(e){
    e.preventDefault();
    $.ajax({
        type:'POST',
        url:'?page=sales&action=saveSale',
        data:{
            folio:$('#folio').val(), // Folio de la venta
            client:$("#selectClient option:selected").attr('value'), // cliente seleccionado
            credit:$('#DescCredit').html(), // nuevo credito del cliente
            status:$('#payMethod option:selected').attr('value'), // estatus de la venta
            subtotal:$('#subtotal').html(), // Subtotal y total de la venta
            total:$('#total').html(), // total de la venta
            products:JSON.stringify(array_product) //array de los productos selecionados
        },
        success:(response)=>{
            if (response === 'done') {
                $('#successData').html('los datos se subieron correctamente');
                // $('#successData').html('Los datos se guardaron correctamente');
                $('#successData').css({'display':'block'});
                setTimeout(() => {
                    $('select').val('Selecciona...');
                    array_product = [];
                    $('#CarProduct').html('');
                    $('span').html('');
                    $('#folio').val('000'+ (parseInt($('#folio').val()) + 1));
                    window.location.href = '?page=sales&action=';
                }, 2000);
            }
        }
    })

    
})
$(document).on('click','.expand', function(e){
    $('#'+$(e.target).attr('data-sale')).toggleClass('visibleTr');
})
// Al seleccionar un metodo de pago
function changePayMethod(){
    if($('#payMethod').val() == 'credito') {
        $('#credit').html($('#credit_limit').html());
    } else {
        $('#credit').html('0');
    }
    CalcTotal();
}
// Al seleccionar un cliente, los datos del credito aparecen en los campos
function getDataClient(){
    var idClient = $("#selectClient option:selected").attr('value');
    if (idClient != undefined) {
        $.ajax({
            url:'?page=sales&action=getDataSelectedClient',
            type:'POST',
            dataType:'json',
            data:{id:idClient},
            success:(response) => {
                $.each(response, function (indice,valor) {
                    if (valor.approved_credit == 0) {
                        $('#approved_credit').html('Aprobado');
                        $('#credit_limit').html(valor.credit_limit);
                        $('#credit_days').html(valor.credit_days);
                        if ($('#payMethod option:selected').val() == 'credito') {
                            $('#credit').html(valor.credit_limit);
                        } else {
                            $('#credit').html('0');
                        }
                    } else {
                        $('#approved_credit').html('No aprobado');
                        $('#credit_limit').html('$00.00');
                        $('#credit_days').html('0');
                        $('#credit').html('0');
                    }
                })
                CalcTotal();
            }
        })
    } else {
        $('#approved_credit').html('');
        $('#credit_limit').html('');
        $('#credit_days').html('');
        $('#credit').html('0');
        CalcTotal();
    }
}
// Elimina el producto de la tabla de venta
function deleteFromCar(id){
    array_product.splice(id,1);
    let string = '', total_price = 0; $('#CarProduct').html("");
    array_product.forEach((product,index) => {
        string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>"+product.Total+"</td><td><a class='btn btn-danger addProduct' onclick='deleteFromCar("+index+","+product.Total+")'>Eliminar</a></td></tr>";
        total_price += product.Total;
        $('#CarProduct').html(string);
    })
    $('#subtotal').html(total_price)
    CalcTotal();
}
function CalcTotal(){
    let subtotal = parseInt($('#subtotal').html());
    let credit = parseInt($('#credit').html());
    let descuentoCredit = credit - subtotal, finalTotal=subtotal-credit, totalDesc = 0, total = 0;
    totalDesc = descuentoCredit > 0 ? descuentoCredit : 0;
    total = finalTotal < 0 ? 0 : finalTotal;
    if ($('#title').html() == 'Editar Venta') {
        if (subtotal != parseInt($('#subtotal').attr('data-subtotal'))) {
            $('#DescCredit').html(totalDesc);
        }
    } else {
        $('#DescCredit').html(totalDesc);
    }
    $('#total').html(total);
}
/* ---------- FUNCIONES PARA LOGIN ----------  */
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