<?php
var_dump($providers);
require_once 'includes/header.php';
?>
    <section class="container">
        <h2 style="margin-bottom:30px;">Nuevo Proveedor</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newProviderForm">
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <div class="form-group row">
                        <label for="inputRfc" class="col-md-12 col-form-label">RFC:</label>
                        <div class="col-md-12">
                            <input type="text" name="rfc" value="<?=isset($providers['RFC']) && !empty($providers['RFC']) ? $providers['RFC'] : '';?>" class="form-control" id="inputRfc" placeholder="RFC" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputComercialName" class="col-md-12">Nombre comercial:</label>
                        <div class="col-md-12">
                            <input type="text" name="comercial_name" value="<?=isset($providers['comercial_name']) && !empty($providers['comercial_name']) ? $providers['comercial_name'] : '';?>" class="form-control" id="inputComercialName" placeholder="Nombre comercial" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputType" class="col-md-12 col-form-label">Tipo de empresa:</label>
                        <div class="col-md-12">
                            <input type="text" name="type" value="<?=isset($providers['type']) && !empty($providers['type']) ? $providers['type'] : '';?>" class="form-control" id="inputType" placeholder="Tipo de empresa" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPhone" class="col-md-12 col-form-label">Teléfono:</label>
                        <div class="col-md-12">
                            <input type="text" name="phone" value="<?=isset($providers['phone']) && !empty($providers['phone']) ? $providers['phone'] : '';?>" class="form-control" id="inputPhone" placeholder="T8700000000" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-12 col-form-label">Direccion:</label>
                        <div class="col-md-6">
                            <label for="inputStreet" class="col-md-12 col-form-label">Calle:</label>
                            <input type="text" name="street" value="<?=isset($providers['street']) && !empty($providers['street']) ? $providers['street'] : '';?>" class="form-control" id="inputStreet" placeholder="Calle" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                        <div class="col-md-6">
                            <label for="inputSuburb" class="col-md-12 col-form-label">Colonia:</label>
                            <input type="text" name="suburb" value="<?=isset($providers['suburb']) && !empty($providers['suburb']) ? $providers['suburb'] : '';?>" class="form-control" id="inputSuburb" placeholder="Colonia" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                        <div class="col-md-6">
                            <label for="inputNumber" class="col-md-12 col-form-label">Número:</label>
                            <input type="text" name="number" value="<?=isset($providers['number']) && !empty($providers['number']) ? $providers['number'] : '';?>" class="form-control" id="inputNumber" placeholder="No. Exterior" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                        <div class="col-md-6">
                            <label for="inputPostalCode" class="col-md-12 col-form-label">Código postal:</label>
                            <input type="text" name="postal_code" value="<?=isset($providers['postal_code']) && !empty($providers['postal_code']) ? $providers['postal_code'] : '';?>" class="form-control" id="inputPostalCode" placeholder="Código Postal" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputTag" class="col-md-12 col-form-label">Etiqueta:</label>
                        <div class="col-md-12">
                            <input type="text" name="tag" value="<?=isset($providers['tag']) && !empty($providers['tag']) ? $providers['tag'] : '';?>" class="form-control" id="inputTag" placeholder="ej. Pesimo provedor" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <a id="senDataProvider" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $providers['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newProvider';?>">Guardar</a>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>