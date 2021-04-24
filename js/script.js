$(document).ready(function(){
    if ($('#flexCheckDefault').is(':checked')) {
        $('#inputCreditLimit').removeAttr('disabled');
        $('#inputCreditDays').removeAttr('disabled');
        $("#flexCheckDefault").attr('value', '0');
    }
    if ($('#table_sales').is(':visible')) {
        $.ajax({
            url:'?page=sales&action=showSales',
            success:(response)=>{
                $('#table_sales').html(response);
            }
        });
    }
    if ($('.dashboard').is(':visible')) {
        var owl = $(".owl-carousel");
        owl.owlCarousel({
            margin: 10,
            nav: true,
            loop: true,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 3,
                },
            },
        });
    }
    if ($('#debtors_table').is(':visible')) {
        $.ajax({
            url:'?page=debtors&action=showDebtors',
            success:(response)=>{
                $('#debtors_table').html(response);
            }
        });
    }
    if ($("#title").html() == 'Ver Venta') {
        document.getElementById('inputPayMethod').value = document.getElementById('tipoVenta').getAttribute('forClient');
        document.getElementById('selectClient').value = document.getElementById('client').getAttribute('forClient');
        $('td > a.deleteProduct').css({'display':'none'});
        $('.editCant').attr('disabled',true);
        $('#user_pay').attr('disabled',true);
        $('#inputPayMethod').attr('disabled',true);
        $('#selectClient').attr('disabled',true);
    } else {
        $('#inputPayMethod').attr('disabled',true);
        getDataClient();
        changePayMethod();
    }
    
    if ($('#title_product').is(':visible')) {
        document.getElementById('inputPStore').value = document.getElementById('label_inputPStore').getAttribute('data-provider');
    }
    $(document).on('click','.deleteProductData', function(e){
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
                    $('.modal-footer').find('a#sendProvider').prop('href',valor.id);
                    // $('.modal-footer').find('#senData').attr('data-id',valor.id);
                    $('#imageProductDelet').attr('src', 'images/'+valor.image);
                    $('#imageProductDelet').attr('alt', 'Eunicodin'+valor.name);
                    $('#nameProductDelete').html(valor.name);
                })
            }
        })
    })
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
        if (validate_new_product()) {
            document.getElementById('failData').style.display = 'none';
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
/* ---------- INSERT CLIENT ON DATABASE ----------  */
$('#sendClient').on('click', function(e) {
    e.preventDefault();
    var actionForm = $(this).attr('data');
    var dataID = $(this).attr('data-id');
    if (actionForm != 'deleteClient') {
        if (validate_new_client()) {
            $.ajax({
                url:'?page=clients&action=saveClient&function='+actionForm+'&id='+dataID,
                data:$('#newClientForm').serialize(),
                type:'POST',
                success:(response)=>{
                    console.log(response);
                    if (response) {
                        $('#failData').css({'display':'none'});
                        $('#successData').html('El cliente se guardo correctamente').css({'display':'block'});
                        setTimeout(() => {
                            window.location.href = '?page=clients&action=';
                        }, 3000);
                    } else {
                        $('#failData').html('El cliente ya existe').css({'display':'block'});
                    }
                }
            })
        } else {
            $('#failData').html('Datos incompletos').css({'display':'block'});
        }
    } else {
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
                    $('#successDataModal').html('Se ha eliminado el cliente').css({'display':'block'});
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
        $('#failDataLimit').removeClass('active');
        $('#failDataDays').removeClass('active');

    }
});
/* ---------- INSERT PROVIDER ON DATABASE ----------  */
$('#senDataProvider').on('click', function(e) {
    e.preventDefault();
    var actionForm = $(this).attr('data');
    var dataID = $(this).attr('data-id');
    if (actionForm != 'deleteProvider') {
        if (validate_new_provider()) {
            $('#failData').css({'display':'none'});
            $.ajax({
                url:'?page=providers&action=saveProvider&function='+actionForm+'&id='+dataID,
                data:$('#newProviderForm').serialize(),
                type:'POST',
                success:(response)=>{
                    if (response) {
                        $('#failData').css({'display':'none'});
                        $('#successData').html('El proveedor  se guardo correctamente').css({'display':'block'});
                        setTimeout(() => {
                            window.location.href = '?page=providers&action=';
                        }, 3000);
                    }
                }
            })
        } else {
            $('#failData').html('Datos incompletos').css({'display':'block'});
        }
    } else {
        $.ajax({
            url:'?page=providers&action=saveProvider',
            data: {
                'function': actionForm,
                'id': $(this).attr('href')
            },
            type:'POST',
            success:function(response){
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
$('#selectClient').on('change', function(){getDataClient();})

$('#inputPayMethod').on('change', function(){changePayMethod();})
// agregar productos a la tabla de la venta(Validado)
filter_array = [];
$('.addProduct').on('click', function(e){
    $('#failData').css({'display':'none'});
    e.preventDefault();
    var idProduct = $("#selectProduct option:selected").attr('value');
    var cant = $('#inputCantidadProduct').val();
    if (idProduct != undefined && cant >= 1) {
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
                    $('#failData').html(errorMessage).css({'display':'block'});
                }
                filter_array = [];
                let string = '', total = 0; $('#CarProduct').html("");
                array_product.forEach((product,index) => {
                    string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>$"+(product.Total).toLocaleString('es-MX')+"</td><td><a class='btn btn-danger deleteProduct' onclick='deleteFromCar("+index+","+product.Total+")'><i class='fas fa-trash-alt'></i></a></td></tr>";
                    total += product.Total;
                    $('#CarProduct').html(string);
                })
                $('#subtotal').html('$'+total)
                CalcTotal();
            }
        })
    } else {
        $('#failData').html('Seleccione un producto y agregue la cantidad').css({'display':'block'});
    }

})
// Detecta cual producto se le esta cambiando la cantidad
$(document).on('change','.editCant', function(e){
    $('#failData').css({'display':'none'})
    let limit = $(e.target).attr('max');
    let id = $(e.target).attr('data-id');
    if (parseFloat($(e.target).val()) > parseFloat(limit)){
        $('#failData').html('No hay suficientes productos en stock').css({'display':'block'});
        $(e.target).val(limit);
    }
    if (parseFloat($(e.target).val()) < 1) {
        $('#failData').html('Agregue una cantidad mayor a cero').css({'display':'block'});
    }
    array_product.forEach(product =>{
        if (product.id == id) {
            product.Cantidad = parseFloat($(e.target).val());
            product.Total = product.Cantidad * product.Precio;
        }
    })
    let string = '', total=0; $('#CarProduct').html("");
    array_product.forEach((product,index) => {
        string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>$"+(product.Total).toLocaleString('es-MX')+"</td><td><a class='btn btn-danger deleteProduct' onclick='deleteFromCar("+index+")'><i class='fas fa-trash-alt'></i></a></td></tr>";
        total += product.Total;
        $('#CarProduct').html(string);
    })
    $('#subtotal').html('$'+total);
    CalcTotal();
})
/*
    Al enviar la venta, se tiene que hacer por cada producto.
    • En cliente se debe actulizar el credito nuevo (esto se debe hacer una sola vez. LA venta solo tiene un cliente)
    • En producto se debe reducir el stock (por cada producto en array producto se debe reducir el stock)
    • Se debe enviar el id del cliente, el id del producto, un folio aleatorio, el metodo de pago(pagado, fiado),fecha de la venta, el subtotal y el total
*/
let responseCreditAbonar = 0, saveIncomplete = false;
$(document).on('click','.response', function(e){
    responseCreditAbonar = $(e.currentTarget).attr('data-response');
    if (responseCreditAbonar == 'abonar') {
        $('#InfoDataPay').html('Guarde la venta para aplicar los cambios')
    } else{
        $('#InfoDataPay').removeClass('active');
        $('#user_pay').val('0');
    }
})
$(document).on('click','.saveIncomplete', function(e){
    if ($(e.currentTarget).attr('data-response') === 'saveIt') {
        saveIncomplete = true;
        $('.btn-close').trigger('click');
        $('#senDataSale').trigger('click');
    }
})
$('#senDataSale').on('click', function(e){
    e.preventDefault();
    let totalToPay = $('#total').is(':visible') ? parseFloat($('#total').html().split('$')[1]) : 0;
    let payFromClient = $('#user_pay').is(':visible') ? parseFloat($('#user_pay').val()) : 0;
    let action = $(this).attr('data');
    let abonar = 0;
    if (validateForm('sales')) {
        $('#failGeneral').removeClass('active');
        if (payFromClient < totalToPay && !saveIncomplete) {
            $('#showTheModal').trigger('click');
        } else {
            if (responseCreditAbonar == 'abonar') {
                abonar = payFromClient;
            }
            if (action == 'newSale') {
                $.ajax({
                    type:'POST',
                    url:'?page=sales&action=saveSale',
                    data:{
                        folio: $('#folio').val(), // Folio de la venta
                        client: $("#selectClient option:selected").attr('value'), // cliente seleccionado
                        credit: parseFloat($('#DescCredit').html().split('$')[1]), // nuevo credito del cliente
                        tipoVenta: $('#inputPayMethod option:selected').attr('value'), // Credito o de contado
                        subtotal: parseFloat($('#subtotal').html().split('$')[1]), // total de la venta
                        total: parseFloat($('#total').html().split('$')[1]), // total de la venta
                        abonoACredito: abonar,
                        payFromClient: payFromClient,
                        status: (payFromClient < totalToPay) ? 'Pendiente' : 'Pagada',
                        cambio:$('#successDataPay').attr('data-change') != '' ? $('#successDataPay').attr('data-change') : '',
                        products: JSON.stringify(array_product) //array de los productos selecionados
                    },
                    success:(response)=>{
                        if (response) {
                            $('#successData').html('los datos se guardaron correctamente');
                            $('#successData').css({'display':'block'});
                            setTimeout(() => {
                                window.location.href = '?page=sales&action=';
                            }, 3000);
                        }
                    }
                })
            } 
            if(action == 'editSale') {
                //para el cliente
                let editedCredit = '';
        
                // para el credito
                if ($('#DescCredit').html() != '-') {
                    editedCredit = $('#DescCredit').html();
                }
                console.log(editedCredit);
                console.log($("#selectClient option:selected").attr('value'));
                console.log($('#payMethod option:selected').attr('value'));
                console.log($('#total').html());
                console.log($('#subtotal').html());
                console.log(JSON.stringify(array_product));
                console.log(action);
                $.ajax({
                    type:'POST',
                    url:'?page=sales&action=saveSale&parameter='+$(this).attr('data-id')+'',
                    data:{
                        action:action, //action de la venta
                        client:$("#selectClient option:selected").attr('value'), // cliente seleccionado
                        credit:editedCredit, // nuevo credito del cliente
                        status:$('#payMethod option:selected').attr('value'), // estatus de la venta
                        total:$('#total').html(), // total y total de la venta
                        subtotal:$('#subtotal').html(), // subtotal de la venta
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
                                $('#folio').val('000'+ (parseFloat($('#folio').val()) + 1));
                                window.location.href = '?page=sales&action=';
                            }, 2000);
                        }
                    }
                })
            }
            if (action == 'deleteSale') {
                
                $.ajax({
                    type:'POST',
                    url:'?page=sales&action=saveSale',
                    data: {
                        action: action,
                        id: $(this).attr('href')
                    },
                    success:function(response){
                        console.log(response);
                        if (response) {
                            $('#successDataModal').html('Se ha eliminado la venta');
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
        }   
    } else {
        $('#failGeneral').html('No olvide seleccionar un cliente y agregar productos a la venta').addClass('active');
    }
})
function delete_product(url_product){
    console.log(url_product);
    $.ajax ({
        type:'POST',
        url: '?page=products&action=getProductToDelete',
        data:{id:url_product},
        dataType:'json',
        success:(response) => {
            console.log('llego aqui')
        }
    })
}
$(document).on('click','.expand', function(e){
    $('#'+$(e.target).attr('data-sale')).toggleClass('visibleTr');
})
// Al seleccionar un metodo de pago
let creditClientSelected = 0;
function changePayMethod(){
    if ($('#title').html() != 'Ver Venta') {
        if($('#inputPayMethod').val() == 'credito') {
            $('#creditoClient-box').addClass('active');
            $('#newCreditoClient-box').addClass('active');
            $('#descuentoClient-box').addClass('active');
            $('#credit_limit').html('$'+parseFloat(creditClientSelected));
        } else {
            $('#creditoClient-box').removeClass('active');
            $('#newCreditoClient-box').removeClass('active');
            $('#descuentoClient-box').removeClass('active');
            $('#credit_limit').html('$0');
        }
        CalcTotal();
    }
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
                    if (valor.approved_credit == 'Aprobado') {
                        $('#approved_credit').html('Aprobado');
                        creditClientSelected = valor.credit_limit;
                        $('#inputPayMethod option[value=credito]').attr('disabled',false);
                        if (valor.credit_limit > 0) {
                            if ($('#inputPayMethod option:selected').val() == 'credito') {
                                $('#inputPayMethod option[value=credito]').attr('disabled',false);
                                $('#credit_limit').html('$'+parseFloat(creditClientSelected));
                            } else {
                                $('#credit_limit').html('$0');
                            }
                        } else {
                            if ($('#inputPayMethod option:selected').val() == 'credito') {
                                $('#inputPayMethod').val('contado');
                                $('#creditoClient-box').removeClass('active');
                                $('#newCreditoClient-box').removeClass('active');
                                $('#descuentoClient-box').removeClass('active');
                                $('#credit_limit').html('$0');
                            }
                            $('#credit_limit').html('$0');
                            $('#inputPayMethod option[value=credito]').attr('disabled',true);
                        }
                        
                    } else {
                        $('#approved_credit').html('No aprobado');
                        if ($('#inputPayMethod option:selected').val() == 'credito') {
                            $('#inputPayMethod').val('contado');
                            $('#creditoClient-box').removeClass('active');
                            $('#newCreditoClient-box').removeClass('active');
                            $('#descuentoClient-box').removeClass('active');
                            $('#credit_limit').html('$0');
                        }
                        $('#inputPayMethod option[value=credito]').attr('disabled',true);
                        $('#credit_limit').html('$0');
                    }
                })
                CalcTotal();
            }
        })
        $('#inputPayMethod').attr('disabled',false);
    } else {
        $('#inputPayMethod').attr('disabled',true);
        $('#approved_credit').html('');
        $('#credit_limit').html('$0');
        CalcTotal();
    }
    
}
// Elimina el producto de la tabla de venta
function deleteFromCar(id){
    array_product.splice(id,1);
    let string = '', total_price = 0; $('#CarProduct').html("");
    array_product.forEach((product,index) => {
        string+="<tr><td>"+(index+1)+"</td><td>"+product.Producto+"</td><td>"+product.Precio+"</td><td><input type='number' class='editCant' value='"+product.Cantidad+"' min='1' max='"+product.Disponibles+"' data-id='"+product.id+"'></td><td>"+product.Disponibles+"</td><td>$"+(product.Total).toLocaleString('es-MX')+"</td><td><a class='btn btn-danger deleteProduct' onclick='deleteFromCar("+index+","+product.Total+")'><i class='fas fa-trash-alt'></i></a></td></tr>";
        total_price += product.Total;
        $('#CarProduct').html(string);
    })
    $('#subtotal').html('$'+total_price)
    CalcTotal();
}
function CalcTotal(){
    let subtotal = $('#subtotal').html() != '$0' && $('#subtotal').is(':visible') ? parseFloat($('#subtotal').html().split('$')[1]) : 0;
    let credit = $('#credit_limit').html() != '$0' && $('#credit_limit').is(':visible') ? parseFloat($('#credit_limit').html().split('$')[1]) : 0;
    let descuentoCredit = credit - subtotal, finalTotal=subtotal-credit, totalDesc = 0, total = 0;
    totalDesc = descuentoCredit > 0 ? descuentoCredit : 0;
    total = finalTotal < 0 ? 0 : finalTotal;
    if ($('#title').html() != 'Ver Venta') {
        // if (subtotal != parseInt($('#subtotal').attr('data-subtotal')) || total != $('#total').attr('data-total')) {
        //     $('#DescCredit').html('$'+totalDesc);
        // } else {
        //     $('#DescCredit').html('$0');
        // }
        $('#desc_to_credit').html('$'+subtotal); //descuento al crédito
        $('#DescCredit').html('$'+totalDesc); //nuevo saldo

        $('#total').html('$'+total); // Total
        if (subtotal > 0) {
            payment();
        }
    }
}
function payment(){
    if ($('#user_pay').val() == '') $('#user_pay').val(1);
    let totalToPay = parseFloat($('#total').html().split('$')[1]);
    let subtotalToPay = parseFloat($('#subtotal').html().split('$')[1]);
    let payFromClient = parseFloat($('#user_pay').val());
    let creditTotalPay = parseFloat($('#credit_limit').html().split('$')[1]);
    if ($('#title').html() != 'Ver Venta') {
        if (totalToPay > 0) {
            if (payFromClient >= totalToPay) {
                $('#InfoDataPay').removeClass('active');
                $('#failDataPay').removeClass('active');
                $('#successDataPay').html('Su cambio es de: $'+ parseFloat(payFromClient - totalToPay)).addClass('active');
                $('#successDataPay').attr('data-change',payFromClient - totalToPay);
            }
            if(payFromClient < totalToPay && payFromClient > 0 && $('#inputPayMethod option:selected').val() == 'contado') {
                $('#InfoDataPay').removeClass('active');
                $('#successDataPay').removeClass('active');
                $('#failDataPay').html('La cantidad es insuficiente.').addClass('active');;
            }
            if(creditTotalPay < subtotalToPay && $('#inputPayMethod option:selected').val() == 'credito' && payFromClient < totalToPay) {
                $('#successDataPay').removeClass('active');
                $('#failDataPay').html('El crédito es insuficiente.').addClass('active');
            }
        } else if(totalToPay <= 0 && subtotalToPay > 0 && payFromClient > 0){
            $('#successDataPay').removeClass('active');
            $('#failDataPay').removeClass('active');
            $('#InfoDataPay').html('<p>El total de la venta es $0. ¿Deseas abonar al credito del cliente?</p><a data-response="abonar" class="response"> Si </a><a data-response="noAbonar" class="response"> No </a>').addClass('active');
        }
    }
}
function validateForm(formulario){
    if($('#title').html() == 'Nueva Venta'){
        if (formulario == 'sales') {
            let client = $("#selectClient option:selected").attr('value') != undefined ? true : false;
            let productos = array_product.length > 0 ? true : false;
            if (client && productos) {
                return true;
            }
        }
    } else {
        return true;
    }
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
    var input = document.getElementById('file'); //con esto seleccionamos el input file
    $(input).trigger('click');//le damos el evento clic al input file (de manera indirecta con el trigger)
    input.addEventListener('change', function(e){ //cuando cambie el valor del input llamara a la funcion showImag
        e.preventDefault();
        showImage(document.getElementById('file'));
    })
}
function showImage(input) {
    if(input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/png') {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementsByClassName('box-image')[0].style.backgroundImage = 'url('+ e.target.result+')';
            $('#failDataImage').removeClass('active');
        }
        reader.readAsDataURL(input.files[0]);
    }else {
        $('#failDataImage').html('Ingrese solo imagenes con extencion jpg/png').addClass('active');
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
$('#filtroFolio').on('keypress', function(e){
    if((e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 186 && e.keyCode <= 192) || e.keyCode == 18 || e.keyCode == 19 || e.keyCode == 21 && e.keyCode == 222){
        return false;
    }
})
// filtros ventas
function filtrar(filtro){
    let filter_type = '';
    if (filtro == 'folio') {
        filter_type = $('#filtroFolio').val();
    } else if(filtro == 'pay_method'){
        if ($('#filtroTipo').val() != '') filter_type = $('#filtroTipo').val();
    } else if(filtro == 'cliente'){
        filter_type = $('#filtroCliente').val();
    } else if (filtro == 'status') {
        if ($('#filtroStatus').val() != '') filter_type = $('#filtroStatus').val();
    }
    $.ajax({
        type:'POST',
        url:'?page=sales&action=salesFilter',
        data:{filter:filtro,filter_per:filter_type},
        success:(response)=>{
            $('#table_sales').html(response);
        }
    })
}
function filter_products(filtro){
    let filter_type = '';
    if (filtro == 'name') {
        filter_type = $('#filtroNombre').val();
    } else if (filtro == 'procedence_store') {
        filter_type = $('#filtroProveedor').val();
    } else if(filtro == 'status'){
        if ($('#filtroStatus').val() != '') filter_type = $('#filtroStatus').val();
    } else if(filtro == 'price'){
        filter_type = $('#filtroPrecio').val();
    } else if(filtro == 'quantity'){
        if ($('#filtroDisponiblesType').val() != '') filter_type = $('#filtroDisponiblesType').val();
    }

    $.ajax({
        type:'POST',
        url:'?page=products&action=filterProducts',
        data:{ filter : filtro, filter_per : filter_type, quantity_filter:$('#filtroDisponibles').val() },
        success:(response)=>{
            $('#table-filter-products').html(response);
        }
    })
}
function filter_clients(filtro){
    let filter_type = '';
    if (filtro == 'name') {
        filter_type = $('#filtroNombre').val();
    } else if (filtro == 'email') {
        filter_type = $('#filtroEmail').val();
    } else if(filtro == 'approved_credit'){
        if ($('#filtroCredito').val() != '') filter_type = $('#filtroCredito').val();
    } else if(filtro == 'bank_reference'){
        filter_type = $('#filtroBankReference').val();
    }

    $.ajax({
        type:'POST',
        url:'?page=clients&action=filterClients',
        data:{ filter : filtro, filter_per : filter_type },
        success:(response)=>{
            $('#table-filter-clients').html(response);
        }
    })
}
function filter_proveedores(filtro){
    let filter_type = '';
    if (filtro == 'RFC') {
        filter_type = $('#filtroRFC').val();
    } else if (filtro == 'comercial_name') {
        filter_type = $('#filtroComercial').val();
    } else if(filtro == 'type'){
        filter_type = $('#filtroTipo').val();
    } else if(filtro == 'tag'){
        filter_type = $('#filtroEtiqueta').val();
    }

    $.ajax({
        type:'POST',
        url:'?page=providers&action=filterProvider',
        data:{ filter : filtro, filter_per : filter_type },
        success:(response)=>{
            $('#table-filter-provider').html(response);
        }
    })
}
function filter_pagos(filtro){
    let filter_type = '';
    if (filtro == 'name') {
        filter_type = $('#filtroNombre').val();
    } else if (filtro == 'desde') {
        if ($('#filtroFrom').val() != '') filter_type = $('#filtroFrom').val();
    } else if(filtro == 'pay_method'){
        if ($('#filtroPayMethod').val() != '') filter_type = $('#filtroPayMethod').val();
    }
    $.ajax({
        type:'POST',
        url:'?page=payments&action=filterPayments',
        data:{ filter : filtro, filter_per : filter_type },
        success:(response)=>{
            $('#table-filter-payments').html(response);
        }
    })
}
//Validacion nuevo producto
function validate_new_product(){
    let all_done_name = false, all_done_providerP = false, all_done_provider = false, all_done_price = false, all_done_quantity = false;
    let name = $('#inputName').val() != '' ?  $('#inputName').val() : false;
    let price_provider = $('#inputPrice').val() != '' ?  $('#inputPrice').val() : false;
    let provider = $('#inputPStore option:selected').val() != '' ? $('#inputPStore option:selected').val(): false;
    let price = $('#inputSPrice').val() != '' ?  $('#inputSPrice').val() : false;
    let quantity = $('#inputQuantity').val() != '' ?  $('#inputQuantity').val() : false;
    
    if (name) {
        // !validate_input_string(name) ? $('#failDataName').html('No ingrese numeros en la ').addClass('active') : $('#failDataName').removeClass('active')
        $('#failDataName').removeClass('active');
        all_done_name = true;
    } else {
        $('#failDataName').html('Ingrese el nombre del producto para continuar').addClass('active');
    }
    
    if (price_provider) {
        if(validate_input_number(price_provider)){
            $('#failDataProviderPrice').removeClass('active')
            all_done_providerP = true;
        } else {
            $('#failDataProviderPrice').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataProviderPrice').html('Ingrese el precio del proveedor para continuar').addClass('active');
    }
    
    if (provider) {
        $('#failDataProvider').removeClass('active');
        all_done_provider = true;
    } else {
        $('#failDataProvider').html('Seleccione un proveedor para continuar').addClass('active');
    }
    
    if (price) {
        if(validate_input_number(price)) {
            $('#failDataStorePrice').removeClass('active')
            all_done_price = true;
        }else {
            $('#failDataStorePrice').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataStorePrice').html('Ingrese el precio del producto para continuar').addClass('active');
    }
    
    if (quantity) {
        if(validate_input_number(quantity)) {
            $('#failDataQuantity').removeClass('active')
            all_done_quantity = true;
        }else {
            $('#failDataQuantity').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataQuantity').html('Ingrese el stock del producto para continuar').addClass('active');
    }

    if (all_done_name && all_done_providerP && all_done_provider && all_done_price && all_done_quantity) {
        return true;
    }
}
function validate_new_client(){
    let all_done_name = false, all_done_email = false, all_done_phone = false, all_done_limit = false, all_done_reference = false, all_done_days = false;
    let name = $('#inputName').val() != '' ?  $('#inputName').val() : false;
    let inputEmail = $('#inputEmail').val() != '' ?  $('#inputEmail').val() : false;
    let inputPhone = $('#inputPhone').val() != '' ? $('#inputPhone').val(): false;
    let inputCreditLimit = $('#inputCreditLimit').val() != '' ?  $('#inputCreditLimit').val() : false;
    let inputCreditDays = $('#inputCreditDays').val() != '' ?  $('#inputCreditDays').val() : false;
    let inputBankReference = $('#inputBankReference').val() != '' ?  $('#inputBankReference').val() : false;
    
    if (name) {
        if(validate_input_string(name)){
            $('#failDataName').removeClass('active');
            all_done_name = true;
        } else {
            $('#failDataName').html('Ingrese solo letras').addClass('active');
        }
    } else {
        $('#failDataName').html('Ingrese el nombre del cliente para continuar').addClass('active');
    }
    
    if (inputEmail) {
        if(validate_input_email(inputEmail)){
            $('#failDataEmail').removeClass('active')
            all_done_email = true;
        } else {
            $('#failDataEmail').html('Correo electronico no valido').addClass('active');
        }
    } else {
        $('#failDataEmail').removeClass('active')
        all_done_email = true;
    }
    
    if (inputPhone) {
        if (validate_input_number(inputPhone)) {
            if (inputPhone.length <= 10) {
                $('#failDataPhone').removeClass('active');
                all_done_phone = true;
            } else {
                $('#failDataPhone').html('Numero de telefono no valido').addClass('active');
            }
        } else {
            $('#failDataPhone').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataPhone').removeClass('active');
        all_done_phone = true;
    }
    
    if (!$('#inputCreditLimit').is(':disabled')) {
        if (inputCreditLimit) {
            if(validate_input_number(inputCreditLimit)) {
                $('#failDataLimit').removeClass('active')
                all_done_limit = true;
            }else {
                $('#failDataLimit').html('Ingrese solo numeros').addClass('active');
            }
        } else {
            $('#failDataLimit').html('Ingresa el limite de crédito para continuar').addClass('active');
        }
    } else {
        $('#failDataLimit').removeClass('active')
        all_done_limit = true;
    }

    if (!$('#inputCreditDays').is(':disabled')) {
        if (inputCreditDays) {
            if(validate_input_number(inputCreditDays)) {
                $('#failDataDays').removeClass('active')
                all_done_days = true;
            }else {
                $('#failDataDays').html('Ingrese solo numeros').addClass('active');
            }
        } else {
            $('#failDataDays').html('Ingrese los dias de credito para continuar').addClass('active');
        }
    } else {
        $('#failDataDays').removeClass('active')
        all_done_days = true;
    }
    
    if (inputBankReference) {
        $('#failDataReferencia').removeClass('active')
        all_done_reference = true;
    } else {
        $('#failDataReferencia').removeClass('active')
        all_done_reference = true;
    }

    if (all_done_name && all_done_email && all_done_phone && all_done_limit && all_done_reference && all_done_days) {
        return true;
    }
}
function validate_new_provider(){
    let all_done_rfc = false, all_done_comercial = false, all_done_type = false, all_done_phone = false, all_done_postal_code = false, all_done_tag = false;
    let inputRfc = $('#inputRfc').val() != '' ?  $('#inputRfc').val() : false;
    let inputComercialName = $('#inputComercialName').val() != '' ?  $('#inputComercialName').val() : false;
    let inputType = $('#inputType').val() != '' ? $('#inputType').val(): false;
    let inputPhone = $('#inputPhone').val() != '' ?  $('#inputPhone').val() : false;
    let inputTag = $('#inputTag').val() != '' ?  $('#inputTag').val() : false;
    let inputPostalCode = $('#inputPostalCode').val() != '' ?  $('#inputPostalCode').val() : false;
        
    if (inputComercialName) {
        if(validate_input_string(inputComercialName)){
            $('#failDataInputComercialName').removeClass('active')
            all_done_comercial = true;
        } else {
            $('#failDataInputComercialName').html('Ingrese solo letras').addClass('active');
        }
    } else {
        $('#failDataInputComercialName').html('Ingrese un nombre valido').addClass('active');
    }
    
    if (inputType) {
        if (validate_input_string(inputType)) {
            $('#failDataInputType').removeClass('active');
            all_done_type = true;
        } else {
            $('#failDataInputType').html('Ingrese solo letras').addClass('active');
        }
    } else {
        $('#failDataInputType').removeClass('active');
        all_done_type = true;
    }
    
    if (inputPhone) {
        if(validate_input_number(inputPhone)) {
            if (inputPhone.length <= 10) {
                $('#failDataInputPhone').removeClass('active')
                all_done_phone = true;
            } else {
                $('#failDataInputPhone').html('Numero de telefono no valido').addClass('active');
            }
        }else {
            $('#failDataInputPhone').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataInputPhone').removeClass('active')
        all_done_phone = true;
    }

    if (inputTag) {
        if (validate_input_string(inputTag)) {
            $('#failDataInputTag').removeClass('active');
            all_done_tag = true;
        } else {
            $('#failDataInputTag').html('Ingrese solo letras').addClass('active');
        }
    } else {
        $('#failDataInputTag').removeClass('active');
        all_done_tag = true;
    }

    if (inputPostalCode) {
        if (validate_input_number(inputPostalCode)) {
            $('#failDataInputPostalCode').removeClass('active');
            all_done_postal_code = true;
        } else {
            $('#failDataInputPostalCode').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataInputPostalCode').removeClass('active');
        all_done_postal_code = true;
    }

    if (all_done_comercial && all_done_type && all_done_phone && all_done_postal_code && all_done_tag) {
        return true;
    }
}
function validate_new_payment(){
    let all_done_concept = false, all_done_abono = false, all_done_client = false, all_done_pay_method = false;
    let concept = $('#inputConcepto').val() != '' ?  $('#inputConcepto').val() : false;
    let client = $('#client_payments_select option:selected').val() != '' ? $('#client_payments_select option:selected').val(): false;
    let abono = $('#user_abono').val() != '' ?  $('#user_abono').val() : false;
    let pay_method = $('#pay_method_select option:selected').val() != 'Selecciona...' ? $('#pay_method_select option:selected').val(): false;
        
    if (concept) {
        $('#failDataConcepto').removeClass('active')
        all_done_concept = true;
    } else {
        $('#failDataConcepto').removeClass('active')
        all_done_concept = true;
    }
    
    if (client) {
        $('#failData').removeClass('active');
        all_done_client = true;
    } else {
        $('#failData').html('Seleccione un cliente para continuar').addClass('active');
    }

    if (pay_method) {
        $('#failData').removeClass('active');
        all_done_pay_method = true;
    } else {
        $('#failDataMetodo').html('Seleccione un método de pago para continuar').addClass('active');
    }
    
    if (abono && !$('#user_abono').is(':disabled') && parseFloat(abono) > 0) {
        if(validate_input_number(abono)) {
            $('#failDataClientPay').removeClass('active')
            all_done_abono = true;
        }else {
            $('#failDataClientPay').html('Ingrese solo numeros').addClass('active');
        }
    } else {
        $('#failDataClientPay').html('Ingrese monto a abonar').addClass('active');
    }

    if (all_done_abono && all_done_concept && all_done_client && all_done_pay_method) {
        return true;
    }
}
function validate_input_string(texto){
    var regex = /^[a-zA-Z ]+$/;
    return regex.test(texto);
}  
function validate_input_email(texto){
    var regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return regex.test(texto);
}  
function validate_input_number(texto){
    var regex = /^[0-9,.]+$/;
    return regex.test(texto);
}
$(document).on('click','.list_items',function(e){
    let toShow = $(e.currentTarget).attr('data-element');
    let siblingToShow = $(e.currentTarget).siblings('a').attr('data-element');
    if (!$('#'+toShow).hasClass('active')) {
        $('#'+toShow).addClass('active');
        $('#'+siblingToShow).removeClass('active');
    } else {
        $('#'+siblingToShow).removeClass('active');
    }
})
function eliminarVenta(url){
    $.ajax({
        url:'?page=sales&action=getSaleToDelete&id='+url,
        dataType:'json',
        success:(response)=>{
            console.log(response);
            // Con esta funcion, puedo recorrer un array que viene de php
            $.each(response, function (indice,valor) {
                $('.modal-footer').find('a#senDataSale').prop('href',valor.id);
                // $('.modal-footer').find('#senData').attr('data-id',valor.id);
                $('#folioSale').html(valor.folio);
            })
        }
    })
}
$(document).on('click','.deleteDataProduct', function(e){
    eliminarProducto($(e.currentTarget).attr('data-element'));
})
function eliminarProducto(url){
    let arrayObjetoResponse;
    $.ajax({
        url:'?page=products&action=getProductToDelete&id='+url,
        dataType:'json',
        success:(response) => {
            arrayObjetoResponse = response;
            console.log(response);
            $.each(response, function (indice,valor) {
                console.log(valor);
                $('.modal-footer').find('a#senData').prop('href',valor.id);
                if (valor.image != '') {   
                    $('#imageProductDelet').attr('src', 'images/'+valor.image);
                } else {
                    $('#imageProductDelet').attr('src', 'https://images.unsplash.com/photo-1454789548928-9efd52dc4031?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80');
                }
                $('#imageProductDelet').attr('alt', 'Eunicodin'+valor.name);
                $('#nameProductDelete').html(valor.name);
            })
        }
    })
    // return arrayObjetoResponse;
}
$(document).on('click','.deleteClientData', function(e){
    eliminarCliente($(e.currentTarget).attr('data-element'));
})
function eliminarCliente(url){
    $.ajax({
        url:'?page=clients&action=getClientToDelete&id='+url,
        dataType:'json',
        success:(response) => {
            console.log(response);
            $.each(response, function (indice,valor) {
                console.log(valor);
                $('.modal-footer').find('a#sendClient').prop('href',valor.id);
                $('#nameClientDelete').html(valor.name);
                $('#compras_cliente').html(valor.totalCompras);
                $('#credit_cliente').html('$' + valor.credit_limit);
            })
        }
    })
}
$(document).on('click','.deleteProviderData', function(e){
    eliminarProveedor($(e.currentTarget).attr('data-element'));
})
function eliminarProveedor(url){
    $.ajax({
        url:'?page=providers&action=getProviderToDelete&id='+url,
        dataType:'json',
        success:(response) => {
            console.log(response);
            $.each(response, function (indice,valor) {
                console.log(valor);
                $('.modal-footer').find('a#senDataProvider').prop('href',valor.id);
                $('#nameProviderDelete').html(valor.comercial_name);
                $('#products_provider').html(valor.totalProductsProvider);
            })
        }
    })
}
$(document).on('click','.expand-sales', function(e){
    $('#'+$(e.target).attr('data-debtor')).toggleClass('visibleTr');
})
$('.input-number-validate').on('keyup', function(){
    if (validate_input_number($(this).val())) {
        $('#failDataClientPay').removeClass('active');
    } else {
        $('#failDataClientPay').html('ingrese solo numeros').addClass('active');
    }
})
$('#senDataPayment').on('click', function(e){
    e.preventDefault();
    if ($('#initial_debt').html() != '$0') {
        $('#failData').css({'display':'none'});
        if (validate_new_payment()) {
            $.ajax({
                type:'POST',
                url:'?page=payments&action=savePayment',
                data:{
                    concepto:$('#inputConcepto').val() != '' ? $('#inputConcepto').val() : '',
                    abono:parseFloat($('#user_abono').val()),
                    client:$('#client_payments_select option:selected').val(),
                    deuda_actual:parseFloat($('#initial_debt').html().split('$')[1]),
                    pay_method:$('#pay_method_select option:selected').val(),
                    restant:parseFloat($('#restant_debt').html().split('$')[1]),
                },
                success:(response)=>{
                    if (response) {
                        $('#successData').html('Los datos se guardaron correctamente');
                        $('#successData').css({'display':'block'});
                        // return false;
                    }
                    setTimeout(() => {
                        $('#successData').css({'display':'none'});
                        window.location.href = '?page=payments&action=';
                    }, 3000);
                }
            })
        }
    } else {
        $('#failData').html('El cliente no es deudor, seleccione otro cliente').css({'display':'block'});
    }
})
$('#client_payments_select').on('change', function(){
    if($('#client_payments_select option:selected').val() != ''){
        $('#failData').css({'display':'none'});
        let id_client = $("#client_payments_select option:selected").attr('value');
        $.ajax({
            url:'?page=payments&action=selectDataDebtors&id=',
            type:'POST',
            data:{id:id_client},
            dataType:'json',
            success:(response) => {
                console.log(response);
                // {total_debt: "404.00", restant_debt: "354.00"}
                if(response.length > 0){
                    $('#inputConcepto').attr('disabled',false);
                    $('#user_abono').attr('disabled',false);
                    $('#pay_method_select').attr('disabled',false);
                    response.forEach(element => {
                        if (element.total_debt != '') {
                            $('#initial_debt').html('$'+element.total_debt);
                            $('#total_abono').html('$'+element.abonos);
                            $('#restant_debt').html('$'+element.restant_debt);
                        }
                    });
                } else {
                    $('#inputConcepto').attr('disabled',true);
                    $('#user_abono').attr('disabled',true);
                    $('#pay_method_select').attr('disabled',true);
                    $('#initial_debt').html('$0');
                    $('#total_abono').html('$0');
                    $('#restant_debt').html('$0');
                }
            }
        })
    } else {
        $('#failData').html('Seleccione un cliente para continuar').css({'display':'block'});
    }
})

//para el ticket
$(document).on('click','.downloadTicket', (e)=>{
    let id_sale = $(e.currentTarget).attr('data-id');
    let folio_sale = $(e.currentTarget).attr('data-folio');
    console.log('?page=sales&action=dataToTicket&id='+id_sale+'&folio='+folio_sale);
    window.location.href = '?page=sales&action=dataToTicket&id='+id_sale+'&folio='+folio_sale;
})
// function onKeyDownHandler(event) {
//     event.stopPropagation()
//     var parent = event.target;
//     var code = event.keyCode;//event.which || 
    
//     //ver si el padre es texto o numero
//     if (parent.getAttribute('name') == 'name' || parent.getAttribute('name') == 'procedence_store') {
//         if(!((code > 64 && code < 91) || code == 32 || code == 186 || code == 16 || code == 192 || code == 37 || code == 39 || code == 9 || code == 20 || code == 8)){
//             return false; //se retorna falso para que no se escriba el caracter que no va
//         }
//     } else {
//         if(!((code > 47 && code < 58) || (code > 95 && code < 106) || code == 190 || code == 110 || code == 16 || code == 37 || code == 39 || code == 9 || code == 8)){
//             return false;
//         }
//     }   
// }
// $(document).on('click','.deleteData',function(e){
//     e.preventDefault();
//     $.ajax({
//         url:$(e.currentTarget).attr('data-href'),
//         dataType:'json',
//         success:function(response) {
//             console.log('al menos llego aqui');
//             console.log(response);
//             // Con esta funcion, puedo recorrer un array que viene de php
//             // $.each(response, function (indice,valor) {
//             //     $('.modal-footer').find('a#senData').prop('href',valor.id);
//             //     // $('.modal-footer').find('a#sendClient').prop('href',valor.id);
//             //     // $('.modal-footer').find('a#sendProvider').prop('href',valor.id);
//             //     // $('.modal-footer').find('#senData').attr('data-id',valor.id);
//             //     if (valor.image != '') {   
//             //         $('#imageProductDelet').attr('src', 'images/'+valor.image);
//             //     } else {
//             //         $('#imageProductDelet').attr('src', 'https://images.unsplash.com/photo-1454789548928-9efd52dc4031?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80');
//             //     }
//             //     $('#imageProductDelet').attr('alt', 'Eunicodin'+valor.name);
//             //     $('#nameProductDelete').html(valor.name);
//             // })
//         }
//     })
// })