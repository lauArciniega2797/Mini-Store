<?php include 'includes/header.php';
var_dump($sale, $action);
?>
    <section class="container">
        <h2 id="title" style="margin-bottom:30px;"><?= isset($action) && !empty($action) ? 'Editar' : 'Nueva';?> Venta</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newSaleForm">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <p>Informacion del cliente</p>
                    <div class="form-group row">
                        <label forClient="<?=$sale[0]['id_client']?>" id="client" class="col-md-12 col-form-label">Selecciona un cliente:</label>
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
                    <br><br>
                    <p id="estatus" forClient="<?= $sale[0]['pay_method']?>">Estatus de la venta</p>
                    <div class="form-group">
                        <select id="payMethod" class="form-select" aria-label="Default select example">
                            <!-- <option>Selecciona...</option> -->
                            <option value="credito">Crédito</option>
                            <option value="contado" selected>Contado</option>
                            <option value="pending">Pendiente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="row">
                        <p>Folio de la venta:</p>
                        <input type="text" id="folio" value="<?= isset($sale[0]['folio']) && !empty($sale[0]['folio']) ? $sale[0]['folio'] : '000'.((int)$count[0]['COUNT(*)'] == 0 ? 1 : (int)$count[0]['COUNT(*)'] + 1);?>" disabled>
                    </div>
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
                                <input type="number" name="cantidadProduct" min=1 value='1' class="form-control" id="inputCantidadProduct" autocomplete="off">
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
                            <tbody id="CarProduct">
                            <script> var array_product = []; </script>
                        <?php
                            if (isset($sale) && !empty($sale)) {
                                foreach ($sale[1] as $value) {
                                    echo "<script>";
                                    echo "  array_product.push({id:".$value['id_product'].",Producto:'".$value['nombre']."', Precio:".$value['precio'].",Cantidad:".$value['cantidad'].",Disponibles:".$value['disponibles'].",Total:".$value['total']."})";
                                    echo "</script>";
                        ?>
                                <tr>
                                    <td><?=$value['index']?></td>
                                    <td><?=$value['nombre']?></td>
                                    <td><?=$value['precio']?></td>
                                    <td>
                                        <input type='number' class='editCant' value='<?=$value['cantidad']?>' min='1' max='<?=$value['disponibles']?>' data-id='<?=$value['id_product']?>'>
                                    </td>
                                    <td><?=$value['disponibles']?></td>
                                    <td><?=$value['total']?></td>
                                    <td>
                                    <a class='btn btn-danger addProduct' onclick='deleteFromCar("<?=$value["index"] - 1 ?>","<?=$value["total"]?>")'>Eliminar</a>
                                    </td>
                                </tr>
                        <?php
                                }
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <p>RESUMEN</p>
                        <div class="col-md-3">
                            <p>Crédito: $<b><span id="credit">0</span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Crédito Nuevo: $<b><span id="DescCredit">0</span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Subtotal: $<b><span id="subtotal"><?= isset($sale) && !empty($sale) ? $sale[0]['subtotal'] : '';?></span></b></p>
                        </div>
                        <div class="col-md-3">
                            <p>Total: $<b><span id="total"></span></b></p>
                        </div>
                    </div>
                    <a id="senDataSale" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $sale[0]['id_product'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newSale';?>">Guardar Venta</a>
                    <?= isset($sale) && !empty($sale) ? "<a href='?page=sales&action=' class='btn btn-danger'>Cancelar</a>" : '';?>
                </div>
            </div>
        </form>
    </section>
    <script>
        // console.log($sale[1]);
        if (document.getElementById("title").innerHTML == 'Editar Venta') {
            document.getElementById('selectClient').value = document.getElementById('client').getAttribute('forClient');
            document.getElementById('payMethod').value = document.getElementById('estatus').getAttribute('forClient');
        }
    </script>
<?php include 'includes/footer.php'?>