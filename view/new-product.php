<?php
// var_dump($product[1]);
require_once 'includes/header.php';
?>
    <section>
        <h2 id="title_product" style="margin-bottom:30px;"><?= isset($action) && !empty($action) ? 'Editar' : 'Nuevo';?> Producto</h2>
        <hr>
        <div class="alert alert-primary col-md-12" role="alert">No olvides ingresar la tienda donde la compraste y el precio al que lo compraste</div>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newProductForm" enctype="multipart/form-data" style="">
        <div class="box">
            <div class="box-content">
                <div class="form-group">
                    <label for="inputImage">Seleccione una imagen</label>
                    <div class="box-image" onclick="selectImage()" style="background-image:url(<?=isset($product[0]['image']) && !empty($product[0]['image']) ? 'images/'.$product[0]['image'] : 'https://images.unsplash.com/photo-1454789548928-9efd52dc4031?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80';?>)"></div>
                    <input type="file" name="image" id="file" class="form-control-file">
                    <div class="alert alert-danger" id="failDataImage" role="alert"></div>
                </div>
            </div>
            <div class="box-content">
                    <p class="obligated_camps">Campos obligatorios:  *</p>
                    <div class="form-group">
                        <label for="inputName" class="col-md-12 col-form-label"><b>* Nombre:</b></label>
                        <!-- onkeydown="return onKeyDownHandler(event);" -->
                        <input type="text" name="name" value="<?=isset($product[0]['name']) && !empty($product[0]['name']) ? $product[0]['name'] : '';?>" class="form-control" id="inputName" placeholder="Nombre del producto" autocomplete="off">
                        <div class="alert alert-danger" id="failDataName" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputPrice" class="col-md-12"><b>* Precio de proveedor:</b></label>
                        <input type="text" name="price" value="<?=isset($product[0]['price']) && !empty($product[0]['price']) ? $product[0]['price'] : '';?>" class="form-control" id="inputPrice" placeholder="Tienda proveedor" autocomplete="off">
                        <div class="alert alert-danger" id="failDataProviderPrice" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputPStore" id="label_inputPStore" class="col-md-12 col-form-label" data-provider="<?=isset($product[1]) && !empty($product[1]) ? $product[1] : '';?>"><b>* Proveedor:</b></label>
                        <select name="procedence_store" id="inputPStore" class="form-select">
                            <option value="">Selecciona...</option>
                        <?php foreach ($getProviders as $value): ?>
                            <option value="<?=$value['id']?>"><?=$value['comercial_name']?></option>
                        <?php endforeach; ?>
                        </select>
                        <div class="alert alert-danger" id="failDataProvider" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputSPrice" class="col-md-12 col-form-label"><b>* Precio en la tienda:</b></label>
                        <input type="text" name="store_price" value="<?=isset($product[0]['store_price']) && !empty($product[0]['store_price']) ? $product[0]['store_price'] : '';?>" class="form-control" id="inputSPrice" placeholder="Precio de venta" autocomplete="off">
                        <div class="alert alert-danger" id="failDataStorePrice" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputQuantity" class="col-md-12 col-form-label"><b>* Cantidad:</b></label>
                        <input type="text" name="quantity" value="<?=isset($product[0]['quantity']) && !empty($product[0]['quantity']) ? $product[0]['quantity'] : '';?>" class="form-control" id="inputQuantity" placeholder="Cantidad" autocomplete="off">
                        <div class="alert alert-danger" id="failDataQuantity" role="alert"></div>
                    </div>
                    <a id="senData" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $product[0]['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newProduct';?>">Guardar</a>
                    <?= isset($product) && !empty($product) ? "<a href='?page=products&action=' class='btn btn-danger'>Regresar</a>" : '';?>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>