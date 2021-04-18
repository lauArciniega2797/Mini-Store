<?php
  require_once 'includes/header.php';
?>
<section>
    <article>
        <h2 id="title" style="margin-bottom:30px;">Historial de Pagos</h2>
        <hr>
        <a href='?page=payments&action=newPayment' style="float:right;" class="btn btn-primary">Nuevo Pago</a>
        <br><br>
    </article>
    <article id="products" style="margin-top:30px">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                <th>No.</th>
                    <th>
                        <p>Cliente</p>
                        <input type="text" class="form-control" id="filtroNombre" onkeyup="filter_pagos('name')">
                    </th>
                    <th>Total</th>
                    <th>Abono</th>
                    <th>
                        <p>Metodo de pago</p>
                        <select name="status" id="filtroPayMethod" class="form-select" onchange="filter_pagos('pay_method')">
                            <option value="">Selecciona...</option>
                            <option value="contado">Contado</option>
                            <option value="deposito">Transferencia bancaria</option>
                        </select>
                    </th>
                    <th>Concepto</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody id="table-filter-payments">
            <?php if (count($payments) > 0): ?>
                <?php foreach ($payments as $index => $payment): ?>
                    <tr>
                        <td><?=($index + 1)?></td>
                        <td><?=$payment['name']?></td>
                        <td><?=$payment['total']?></td>
                        <td><?=$payment['abono']?></td>
                        <td><?=$payment['pay_method']?></td>
                        <td><?=$payment['concepto']?></td>
                        <td><?=$payment['date']?></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr>
                    <td colspan="9" style="padding:0;margin:0"><div style="margin:0;" class="alert alert-primary col-md-12" role="alert">Aún no cuentas con pagos</div></td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="deleteModalPayments" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Deseas eliminar este pago?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <p id="nameClientDelete"></p>
                        <div class="dataClient">
                            <p>Compras realizadas:</p><b><span id="compras_cliente"></span></b>
                        </div>
                        <div class="dataClient">
                            <p>Deuda:</p><b><span id="deuda_cliente">$0.00</span></b>
                        </div>
                        <div class="dataClient">
                            <p>Crédito:</p><b><span id="credit_cliente"></span></b>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero <i class="em em-white_frowning_face" aria-role="presentation" aria-label=""></i></a>
                        <a id="sendClient" href="" type="button" class="btn btn-primary" data="deleteClient">Si, Eliminar <i class="em em-angry" aria-role="presentation" aria-label="ANGRY FACE"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>