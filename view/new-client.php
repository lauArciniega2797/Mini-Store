<?php
require_once 'includes/header.php';
?>

<body>
    <section class="container">
        <h2 style="margin-bottom:30px;">Nuevo Cliente</h2>
        <hr>
        <div class="alert alert-success" id="successData" role="alert"></div>
        <div class="alert alert-danger" id="failData" role="alert"></div>
        <form id="newClientForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <div class="form-group row">
                        <label for="inputName" class="col-md-12 col-form-label">Nombre:</label>
                        <div class="col-md-12">
                            <input type="text" name="name" value="<?=isset($client['name']) && !empty($client['name']) ? $client['name'] : '';?>" class="form-control" id="inputName" placeholder="Nombre del cliente" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPrice" class="col-md-12">Email:</label>
                        <div class="col-md-12">
                            <input type="email" name="email" value="<?=isset($client['email']) && !empty($client['email']) ? $client['email'] : '';?>" class="form-control" id="inputEmail" placeholder="example@example.com" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPStore" class="col-md-12 col-form-label">Teléfono:</label>
                        <div class="col-md-12">
                            <input type="text" name="phone" value="<?=isset($client['phone']) && !empty($client['phone']) ? $client['phone'] : '';?>" class="form-control" id="inputPhone" placeholder="8700000000" autocomplete="off">
                            <!-- <label for="" id="error-message"></label> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSPrice" class="col-md-12 col-form-label">Crédito aprobado:</label>
                        <div class="col-md-6">
                            <div class="form-check">
                                <!-- <input type="checkbox" name="approved_credit"  id="inputAC"> -->
                                <input class="form-check-input" type="checkbox" name="checkCredit" value="<?=isset($client['approved_credit']) && !empty($client['approved_credit']) ? $client['approved_credit'] : '';?>" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Crédito Aprobado
                                </label>
                            </div>
                        </div>
                        <!-- <label for="" id="error-message"></label> -->
                    </div>
                    <div class="form-group row">
                        <label for="inputQuantity" class="col-md-12 col-form-label">Limite de cerdito:</label>
                        <div class="col-md-12">
                            <input type="text" name="credit_limit" value="<?=isset($client['credit_limit']) && !empty($client['credit_limit']) ? $client['credit_limit'] : '';?>" class="form-control" id="inputCreditLimit" placeholder="Limite de crédito" autocomplete="off" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputQuantity" class="col-md-12 col-form-label">Días de crédito:</label>
                        <div class="col-md-12">
                            <input type="text" name="credit_days" value="<?=isset($client['credit_days']) && !empty($client['credit_days']) ? $client['credit_days'] : '';?>" class="form-control" id="inputCreditDays" placeholder="Dias de crédito" autocomplete="off" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputQuantity" class="col-md-12 col-form-label">Referencia bancaria:</label>
                        <div class="col-md-12">
                            <input type="text" name="bank_reference" value="<?=isset($client['bank_reference']) && !empty($client['bank_reference']) ? $client['bank_reference'] : '';?>" class="form-control" id="inputBankReference" placeholder="Referencia bancaria" autocomplete="off">
                        </div>
                    </div>
                    <a id="sendClient" href="javascript(void)" class="btn btn-primary" data-id="<?=isset($action) && !empty($action) ? $client['id'] : '';?>" data="<?=isset($action) && !empty($action) ? $action : 'newClient';?>">Guardar</a>
                </div>
            </div>
        </form>
    </section>
<?php
  require_once 'includes/footer.php';
?>