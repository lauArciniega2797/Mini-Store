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
                        <div class="box-image" onclick="selectImage()"></div>
                        <!--  id="inputImage" -->
                        <input type="file" name="image" id="file" class="form-control-file">
                        <p id="error-message"></p>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="form-group row">
                        <label for="inputName" class="col-md-12 col-form-label">Nombre:</label>
                        <div class="col-md-12">
                            <input type="text" name="name" class="form-control" id="inputName" placeholder="Nombre del producto" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPrice" class="col-md-12">Precio de proveedor:</label>
                        <div class="col-md-12">
                            <input type="text" name="price" class="form-control" id="inputPrice" placeholder="Tienda proveedor" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPStore" class="col-md-12 col-form-label">Tienda de procedencia:</label>
                        <div class="col-md-12">
                            <input type="text" name="procedence_store" class="form-control" id="inputPStore" placeholder="Tienda proveedor" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSPrice" class="col-md-12 col-form-label">Precio:</label>
                        <div class="col-md-12">
                            <input type="text" name="store_price" class="form-control" id="inputSPrice" placeholder="Precio de venta" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputQuantity" class="col-md-12 col-form-label">Cantidad:</label>
                        <div class="col-md-12">
                            <input type="text" name="quantity" class="form-control" id="inputQuantity" placeholder="Cantidad" autocomplete="off" onkeydown="return onKeyDownHandler(event);">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <a id="senData" class="btn btn-primary">Guardar</a>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>