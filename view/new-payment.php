<?php
// var_dump($payments_clients);
require_once 'includes/header.php';
?>
    <section>
        <h2 id="title_payment" style="margin-bottom:30px;"><?= isset($action) && !empty($action) ? '' : 'Nuevo';?> Pago</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newProductForm" enctype="multipart/form-data" style="">
        <div class="box">
            <div class="box-content">
                <div class="clientes">
                        <label forClient="<?=isset($payments) && !empty($payments) ? $payments[0]['id_client'] : '';?>" id="client_payments" class="col-md-12 col-form-label"><b>* Selecciona un cliente:</b></label>
                        <select id="client_payments_select" class="form-select" aria-label="Default select example">
                            <option selected>Selecciona...</option>
                            <?php foreach ($payments_clients as $paymen): ?>
                                <option value="<?=$paymen['id']?>"><?=$paymen['name']?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <div class="dataClientPayments">
                            <p>Total Deuda</p>
                            <span id="initial_debt">$0</span>
                        </div>
                        <div class="dataClientPayments">
                            <p>Total Abonos</p>
                            <span id="total_abono">$0</span>
                        </div>
                        <div class="dataClientPayments">
                            <p>Restante</p>
                            <span id="restant_debt">$0</span>
                        </div>
                    </div>
            </div>
            <div class="box-content">
                    <div class="form-group">
                        <label for="inputConcepto" class="col-md-12"><b>Método de pago:</b></label>
                        <select id="pay_method_select" class="form-select" aria-label="Default select example" disabled>
                            <option selected>Selecciona...</option>
                            <option value="contado">Contado</option>
                            <option value="deposito">Transferencia bancaria</option>
                        </select>
                        <div class="alert alert-danger" id="failDataMetodo" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputConcepto" class="col-md-12"><b>Concepto del pago:</b></label>
                        <input type="text" name="price" class="form-control" id="inputConcepto" placeholder="Ejem. Pago por los chocorroles" autocomplete="off" disabled>
                        <div class="alert alert-danger" id="failDataConcepto" role="alert"></div>
                    </div>
                    <p id="abonoClient-box">
                        <b>Abonar:</b> <br>
                        <span>$</span><input type="text" name="pago" class="input-number-validate" id="user_abono" value="0" disabled>
                        <div class="alert alert-danger" id="failDataClientPay" role="alert"></div>
                    </p>
                    <a id="senDataPayment" href="javascript(void)" class="btn btn-primary">Abonar</a>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>