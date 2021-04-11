<?php
// var_dump($clienos);
  require_once 'includes/header.php';
?>
<section>
    <article>
        <h2 id="title" style="margin-bottom:30px;">Tus Clientes</h2>
        <hr>
        <a href='?page=clients&action=newClient' style="float:right;" class="btn btn-primary">Nuevo Cliente</a>
        <br><br>
    </article>
    <article id="products" style="margin-top:30px">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>
                        <p>Nombre</p>
                        <input type="text" class="form-control" id="filtroNombre" onkeyup="filter_clients('name')">
                    </th>
                    <th>
                        <p>E-mail</p>
                        <input type="text" class="form-control" id="filtroEmail" onkeyup="filter_clients('email')">
                    </th>
                    <th>Teléfono</th>
                    <th>
                        <p>Crédito</p>
                        <select name="status" id="filtroCredito" class="form-select" onchange="filter_clients('approved_credit')">
                            <option value="">Selecciona...</option>
                            <option value="0">Aprobado</option>
                            <option value="1">Rechazado</option>
                        </select>
                    </th>
                    <th>Limite de crédito</th>
                    <th>Días de crédito</th>
                    <th>
                        <p>Referencia bancaria</p>
                        <input type="text" class="form-control" id="filtroBankReference" onkeyup="filter_clients('bank_reference')">
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="table-filter-clients">
            <?php foreach ($client as $index => $clien): ?>
                <tr>
                    <td><?=($index + 1)?></td>
                    <td><?=$clien['name']?></td>
                    <td><?=$clien['email']?></td>
                    <td><?=$clien['phone']?></td>
                    <td><?=$clien['approved_credit']?></td>
                    <td><?=$clien['credit_limit']?></td>
                    <td><?=$clien['credit_days']?></td>
                    <td><?=$clien['bank_reference']?></td>
                    <td><a href="?page=clients&action=newClient&parameter=<?=$clien['id']?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                <!-- Button trigger modal -->
                <a type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" class="deleteProductData btn btn-danger" data-href="?page=clients&action=getClientToDelete&id=<?=$clien['id']?>"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura deseas eliminar este cliente?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <img id="imageProductDelet" style="width:100%;">
                        <p id="nameProductDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero :C</a>
                        <a id="sendClient" href="" type="button" class="btn btn-primary" data="deleteClient">Si, Eliminar >:|</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>