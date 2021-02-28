<?php
require_once 'includes/header.php';
?>

<body>
    <section class="container">
        <h2 style="margin-bottom:30px;">Nuevo Producto</h2>
        <hr>
        <div class="alert alert-primary col-md-12" role="alert">
            No olvides ingresar la tienda donde la compraste y el precio al que lo compraste
        </div>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newProductForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="form-group">
                        <label for="inputImage">Imagen</label>
                        <div class="box-image" onclick="selectImage()" style="background-image:url(<?=isset($product['image']) && !empty($product['image']) ? 'images/'.$product['image'] : 'https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80';?>)"></div>
                        <!--  id="inputImage" -->
                        <input type="file" name="image" id="file" class="form-control-file">
                        <p id="error-message"></p>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="form-group row">
                        <label for="inputName" class="col-md-12 col-form-label">Nombre:</label>
                        <div class="col-md-12">
                            <input type="text" name="name" value="<?=isset($product['name']) && !empty($product['name']) ? $product['name'] : '';?>" class="form-control" id="inputName" placeholder="Nombre del producto" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPrice" class="col-md-12">Precio de proveedor:</label>
                        <div class="col-md-12">
                            <input type="text" name="price" value="<?=isset($product['price']) && !empty($product['price']) ? $product['price'] : '';?>" class="form-control" id="inputPrice" placeholder="Tienda proveedor" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPStore" class="col-md-12 col-form-label">Tienda de procedencia:</label>
                        <div class="col-md-12">
                            <input type="text" name="procedence_store" value="<?=isset($product['procedence_store']) && !empty($product['procedence_store']) ? $product['procedence_store'] : '';?>" class="form-control" id="inputPStore" placeholder="Tienda proveedor" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSPrice" class="col-md-12 col-form-label">Precio:</label>
                        <div class="col-md-12">
                            <input type="text" name="store_price" value="<?=isset($product['store_price']) && !empty($product['store_price']) ? $product['store_price'] : '';?>" class="form-control" id="inputSPrice" placeholder="Precio de venta" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputQuantity" class="col-md-12 col-form-label">Cantidad:</label>
                        <div class="col-md-12">
                            <input type="text" name="quantity" value="<?=isset($product['quantity']) && !empty($product['quantity']) ? $product['quantity'] : '';?>" class="form-control" id="inputQuantity" placeholder="Cantidad" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <a id="senData" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $product['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newProduct';?>">Guardar</a>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>