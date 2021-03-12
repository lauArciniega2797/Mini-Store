<?php include 'includes/header.php'?>
    <section class="container">
        <h2 style="margin-bottom:30px;">Nueva Venta</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newSaleForm">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <p>Informacion del cliente</p>
                    <div class="form-group row">
                        <label for="" class="col-md-12 col-form-label">Selecciona un cliente:</label>
                        <div class="col-md-12">
                            <select id="selectClient" class="form-select" aria-label="Default select example">
                                <option selected>Selecciona...</option>
                                <?php foreach ($clients as $cl): ?>
                                    <option value="<?=$cl['id']?>"><?=$cl['name']?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                        <div class="col-md-12">
                            <p>Credito: <b><span id="approved_credit"></span></b></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <p>Limite de crédito: <b><span id="credit_limit"></span></b></p>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <p>Días de crédito: <b><span id="credit_days"></span></b></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="inputRfc" class="col-md-6 col-form-label">Producto:</label>
                            <select id="selectProduct" class="form-select col-md-6" aria-label="Default select example">
                                <option selected>Selecciona...</option>
                                <?php foreach ($products as $pr): ?>
                                    <option value="<?=$pr['id']?>"><?=$pr['name']?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="cantidadProduct" class="col-md-6 col-form-label">Cantidad:</label>
                                <input type="number" name="cantidadProduct" min=1 class="form-control" id="inputCantidadProduct" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <br>
                            <a href="javascript(void)" class="btn btn-primary addProduct">add</a>
                        </div>
                    </div>
                    <div class="row" style="margin:0;border:1px solid #d2d2d2;">
                        <!-- TABLA CON LOS PRODUCTOS QUE SE VAN AÑADIENDO -->
                        <table style="margin: 30px 0;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Disponibles</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="CarProduct"></tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <p>RESUMEN</p>
                        <div class="col-md-3">
                            <p>Descuento al credito: $<b><span id="DescCredit"></span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Credito: $<b><span id="credit"></span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Subtotal: $<b><span id="subtotal"></span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Total: $<b><span id="total"></span></b></p>
                        </div>
                    </div>
                    <a id="senDataSale" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $providers['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newProvider';?>">Guardar Venta</a>
                    
                </div>
            </div>
        </form>
    </section>
<?php include 'includes/footer.php'?>