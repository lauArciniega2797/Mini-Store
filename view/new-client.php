<?php
require_once 'includes/header.php';
?>
    <section class="container">
    <h2 id="title_product" style="margin-bottom:30px;"><?= isset($action) && !empty($action) ? 'Editar' : 'Nuevo';?> Cliente</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newClientForm" enctype="multipart/form-data">
            <div class="box-content">
                <p class="obligated_camps">Campos obligatorios:  *</p>
                <div class="form-group">
                    <label for="inputName" class="col-md-12 col-form-label"><b>* Nombre:</b></label>
                    <input type="text" name="name" value="<?=isset($client['name']) && !empty($client['name']) ? $client['name'] : '';?>" class="form-control" id="inputName" placeholder="Nombre del cliente" autocomplete="off">
                    <div class="alert alert-danger" id="failDataName" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="inputPrice" class="col-md-12">Email:</label>
                    <input type="email" name="email" value="<?=isset($client['email']) && !empty($client['email']) ? $client['email'] : '';?>" class="form-control" id="inputEmail" placeholder="example@example.com" autocomplete="off">
                    <div class="alert alert-danger" id="failDataEmail" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="inputPStore" class="col-md-12 col-form-label">Teléfono:</label>
                    <input type="text" name="phone" value="<?=isset($client['phone']) && !empty($client['phone']) ? $client['phone'] : '';?>" class="form-control" id="inputPhone" placeholder="8700000000" autocomplete="off">
                    <div class="alert alert-danger" id="failDataPhone" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="inputSPrice" class="col-md-12 col-form-label">Crédito aprobado:</label>
                    <div class="form-check">
                        <!-- <input type="checkbox" name="approved_credit"  id="inputAC"> -->
                        <?php if(isset($client) && !empty($client) && $client['approved_credit'] == 'Aprobado'):?>
                            <input class="form-check-input" type="checkbox" name="checkCredit" value="<?=isset($client['approved_credit']) && !empty($client['approved_credit']) ? $client['approved_credit'] : '1';?>" id="flexCheckDefault" checked>
                        <?php elseif((isset($client) && !empty($client) && $client['approved_credit'] == 'Rechazado')):?>
                            <input class="form-check-input" type="checkbox" name="checkCredit" value="<?=isset($client['approved_credit']) && !empty($client['approved_credit']) ? $client['approved_credit'] : '1';;?>" id="flexCheckDefault">
                        <?php else:?>
                            <input class="form-check-input" type="checkbox" name="checkCredit" value="<?=isset($client['approved_credit']) && !empty($client['approved_credit']) ? $client['approved_credit'] : '1';;?>" id="flexCheckDefault">
                            <div class="alert alert-danger" id="failDataProvider" role="alert"></div>
                        <?php endif;?>
                        <label class="form-check-label" for="flexCheckDefault">
                            Crédito Aprobado
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputQuantity" class="col-md-12 col-form-label">Limite de crédito:</label>
                    <input type="text" name="credit_limit" value="<?=isset($client['credit_limit']) && !empty($client['credit_limit']) ? $client['credit_limit'] : '';?>" class="form-control" id="inputCreditLimit" placeholder="Limite de crédito" autocomplete="off" disabled>
                    <div class="alert alert-danger" id="failDataLimit" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="inputQuantity" class="col-md-12 col-form-label">Días de crédito:</label>
                    <input type="text" name="credit_days" value="<?=isset($client['credit_days']) && !empty($client['credit_days']) ? $client['credit_days'] : '';?>" class="form-control" id="inputCreditDays" placeholder="Dias de crédito" autocomplete="off" disabled>
                    <div class="alert alert-danger" id="failDataDays" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="inputQuantity" class="col-md-12 col-form-label">Referencia bancaria:</label>
                    <input type="text" name="bank_reference" value="<?=isset($client['bank_reference']) && !empty($client['bank_reference']) ? $client['bank_reference'] : '';?>" class="form-control" id="inputBankReference" placeholder="Referencia bancaria" autocomplete="off">
                    <div class="alert alert-danger" id="failDataReferencia" role="alert"></div>
                </div>
                <a id="sendClient" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $client['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newClient';?>">Guardar</a>
                <?= isset($client) && !empty($client) ? "<a href='?page=clients&action=' class='btn btn-danger'>Regresar</a>" : '';?>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>