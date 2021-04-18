<?php include 'includes/header.php';?>
    <section>
        <h2 id="title" style="margin-bottom:30px;"><?= isset($action) && !empty($action) ? 'Ver' : 'Nueva';?> Venta</h2>
        <hr>
        <div class="buttons">
            <a id="senDataSale" style="display:<?=isset($sale) && !empty($sale) ? 'none' : 'block';?>" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $sale[0]['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newSale';?>">Guardar Venta</a>
            <?= isset($sale) && !empty($sale) ? "<a href='?page=sales&action=' class='btn btn-danger'>Regresar</a>" : '';?>
        </div>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failGeneral" role="alert"></div>
        <form id="newSaleForm">
            <div class="box">
                <div class="box-content">
                    <p class="obligated_camps">Campos obligatorios:  *</p>
                    <div class="folio">
                        <p>Folio de la venta:</p>
                        <input type="text" id="folio" value="<?= isset($sale[0]['folio']) && !empty($sale[0]['folio']) ? $sale[0]['folio'] : '000'.((int)$count[0]['COUNT(*)'] == 0 ? 1 : (int)$count[0]['COUNT(*)'] + 1);?>" disabled>
                    </div>
                    <div class="clientes">
                        <label forClient="<?=isset($sale) && !empty($sale) ? $sale[0]['id_client'] : '';?>" id="client" class="col-md-12 col-form-label"><b>* Selecciona un cliente:</b></label>
                        <select id="selectClient" class="form-select" aria-label="Default select example">
                            <option selected>Selecciona...</option>
                            <?php foreach ($clients as $cl): ?>
                                <option value="<?=$cl['id']?>"><?=$cl['name']?></option>
                            <?php endforeach; ?>
                        </select>
                        <p>Credito: <b><span id="approved_credit"></span></b></p>
                    </div>
                    <div class="estatus">
                        <p style="display:<?=isset($sale) && !empty($sale) ? 'block' : 'none' ;?>">Estatus de la venta</p>
                        <div class="form-group">
                            <input id="sale_status" type="text" value="<?=isset($sale) && !empty($sale) ? $sale[0]['status'] : '' ;?>" class="form-control" style="display:<?=isset($sale) && !empty($sale) ? 'block' : 'none' ;?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="box-content">
                    <div class="alert alert-danger" id="failData" role="alert"></div>
                    <div class="select_product_configuration">
                        <div>
                            <label for="inputRfc" class="col-md-6 col-form-label"><b>* Producto:</b></label>
                            <select id="selectProduct" class="form-select col-md-6" aria-label="Default select example">
                                <option selected>Selecciona...</option>
                                <?php foreach ($products as $pr): ?>
                                    <option value="<?=$pr['id']?>"><?=$pr['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="cantidadProduct" class="col-form-label"><b>* Cantidad:</b></label>
                            <input type="number" name="cantidadProduct" min=1 value='1' class="form-control" id="inputCantidadProduct" autocomplete="off">
                        </div>
                        <div>
                            <a style="display:<?=isset($sale) && !empty($sale) ? 'none' : 'block';?>" href="javascript(void)" class="btn btn-primary addProduct"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <!-- TABLA CON LOS PRODUCTOS QUE SE VAN AÑADIENDO -->
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Disponibles</th>
                                <th>Total</th>
                                <th style="display:<?=isset($sale) && !empty($sale) ? 'none' : 'block';?>">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="CarProduct">
                        <script> var array_product = []; </script>
                    <?php
                        if (isset($sale) && !empty($sale)) {
                            foreach ($sale[1] as $value) {
                                echo "<script>";
                                echo "  array_product.push({id:".$value['id_product'].",Producto:'".$value['nombre']."', Precio:".$value['precio'].",Cantidad:".$value['cantidad'].",Disponibles:".$value['disponibles'].",Total:".$value['total'].",Sale:".$value['sale_id']."})";
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
                                <td>$<?=$value['total']?></td>
                                <td>
                                <a class='btn btn-danger deleteProduct' onclick='deleteFromCar("<?=$value["index"] - 1 ?>","<?=$value["total"]?>")'><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                    <?php
                            }
                        }
                    ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-content">
                    <p id="subtotal-box">
                        Subtotal<br>
                        <span id="subtotal" data-subtotal="<?= isset($sale) && !empty($sale) ? $sale[0]['subtotal'] : '' ;?>"><?= isset($sale) && !empty($sale) ? '$'.$sale[0]['subtotal'] : '$0';?></span>
                    </p>
                    <div>
                        <div id="sale_type">
                            <p forClient="<?= isset($sale) && !empty($sale) ? $sale[0]['pay_method'] : '';?>" id="tipoVenta">Tipo de venta:</p>
                            <select id="inputPayMethod" class="form-select" aria-label="Default select example">
                                <option value="contado" selected>Contado</option>
                                <option value="credito">Crédito</option>
                            </select>
                        </div>
                        <p id="creditoClient-box">
                            Crédito del cliente: <b><span id="credit_limit" class="float-left">$0</span></b>
                        </p>
                        <p id="descuentoClient-box">
                            Descuento al crédito: <b><span id="desc_to_credit" class="float-left">$0</span></b>
                        </p>
                        <p id="newCreditoClient-box">
                            Nuevo saldo: <b><span id="DescCredit" class="float-left">$0</span></b>
                        </p>
                        <p id="totalClient-box">
                            Total: <b><span data-total="" id="total" class="float-left"><?= isset($sale) && !empty($sale) ? '$'.$sale[0]['total'] : '$0' ;?></span></b></p>
                        </p>
                        <p id="payClient-box">
                            <b>* Pagó con:</b> <br>
                            <span>$</span><input type="text" class="input-number-validate" name="pago" id="user_pay" value="<?= isset($sale) && !empty($sale) ? $sale[0]['pay_from_client'] : '0' ;?>" onkeyup='payment()' autocomplete=off>
                            <div class="alert alert-danger" id="failDataClientPay" role="alert"></div>
                        </p>
                        <div class="alerts-sale">
                            <div class="alert alert-primary" id="InfoDataPay" role="alert"></div>
                            <div class="alert alert-success" id="successDataPay" data-change="" role="alert"></div>
                            <div class="alert alert-danger" id="failDataPay" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="showTheModal" data-bs-target="#staticBackdrop" style="position:absolute;opacity:0;"></button>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Pago insuficiente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>El pago de la venta es insuficiente. Si lo guarda, la venta pasara a ser un adeudo del cliente</p>
                        <p>¿Desea continuar?</p>
                    </div>
                    <div class="modal-footer">
                        <a data-response="noSaveIt" class="btn btn-secondary saveIncomplete" data-bs-dismiss="modal">Cancelar</a>
                        <a data-response="saveIt" class="btn btn-primary saveIncomplete">De acuerdo!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        
    </script>
<?php include 'includes/footer.php'?>